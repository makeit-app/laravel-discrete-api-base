<?php

/** @noinspection 1PhpFullyQualifiedNameUsageInspection, 1PhpUndefinedClassInspection, 1PhpUndefinedNamespaceInspection, 1PhpUndefinedConstantInspection */

namespace MakeIT\DiscreteApiBase\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use MakeIT\DiscreteApiBase\Actions\AuthenticateAction;
use MakeIT\DiscreteApiBase\Actions\LogoutAction;
use MakeIT\DiscreteApiBase\Actions\PasswordForgotAction;
use MakeIT\DiscreteApiBase\Actions\PasswordResetAction;
use MakeIT\DiscreteApiBase\Actions\ProfileUpdateAction;
use MakeIT\DiscreteApiBase\Actions\RegisterAction;
use MakeIT\DiscreteApiBase\Actions\UserDeleteAction;
use MakeIT\DiscreteApiBase\Console\Commands\InstallCommand;
use MakeIT\DiscreteApiBase\Contracts\AuthenticateContract;
use MakeIT\DiscreteApiBase\Contracts\LogoutContract;
use MakeIT\DiscreteApiBase\Contracts\PasswordForgotContract;
use MakeIT\DiscreteApiBase\Contracts\PasswordResetContract;
use MakeIT\DiscreteApiBase\Contracts\ProfileUpdareContract;
use MakeIT\DiscreteApiBase\Contracts\RegisterContract;
use MakeIT\DiscreteApiBase\Contracts\UserDeleteContract;
use MakeIT\DiscreteApiBase\Models\PersonalAccessToken;

class DiscreteApiBaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'discreteapibase');
    }

    /**
     * Bootstrap any application services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'discreteapi');
        $this->loadJsonTranslationsFrom(__DIR__.'/../../lang');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->configurePersonalAccessToken();
        //
        $this->configurePublishing();
        $this->configureCommands();
        $this->configureRoutes();
        $this->configurePolicies();
        $this->configureObservers();
        $this->configureResponseBindings();
    }

    /**
     * Returns the appropriate PersonalAccessToken Model for the api, as computed in the computeNamespace() method
     */
    protected function configurePersonalAccessToken(): void
    {
        Sanctum::usePersonalAccessTokenModel(compute_namespace().'Models\\PersonalAccessToken');
    }

    /**
     * Configures a poblishes
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }
        $this->publishes([
            __DIR__.'/../config.php' => config_path('discreteapibase.php'),
        ], 'config');
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Configure the commands offered by the application.
     */
    protected function configureCommands(): void
    {
        if (app()->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Configure the routes offered by the application.
     *
     * @throws BindingResolutionException
     */
    protected function configureRoutes(): void
    {
        //dd($this->computeNamespace());
        $parsed = parse_url(config('app.url', 'http://localhost'));
        $domain = $parsed['host'];
        unset($parsed);
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('preload_user_data', (config('discreteapibase.route_namespace') === 'app' ? '\\App\\Http\\Middleware\\DiscreteApiBase\\PreloadUserData' : '\\MakeIT\\DiscreteApiBase\\Http\\Middleware\\PreloadUserData'));
        Route::domain($domain)
            ->middleware(['api', 'preload_user_data'])
            ->namespace(compute_route_namespace())
            ->prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../routes.php');
            });
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(config('discreteapibase.username'))).'|'.$request->ip());

            return Limit::perMinute(1)->by($throttleKey);
        });
        /*
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(1)->by($request->session()->get('login.id'));
        });
        */
    }

    /**
     * Configure Policies
     */
    protected function configurePolicies(): void
    {
        foreach (config('discreteapibase.policiesToRegister', []) as $Model => $Policy) {
            if (class_exists($Model) && class_exists($Policy)) {
                Gate::policy($Model, $Policy);
            }
        }
    }

    /**
     * Configure Observers
     */
    protected function configureObservers(): void
    {
        foreach (config('discreteapibase.observersToRegister') as $Model => $Observer) {
            if (class_exists($Model) && class_exists($Observer)) {
                /** @noinspection PhpUndefinedMethodInspection */
                $Model::observe($Observer);
            }
        }
    }

    /**
     * Configure (registering) the Actions
     * (not an invokeable)
     */
    protected function configureResponseBindings(): void
    {
        $this->app->singleton(RegisterContract::class, RegisterAction::class);
        $this->app->singleton(AuthenticateContract::class, AuthenticateAction::class);
        $this->app->singleton(LogoutContract::class, LogoutAction::class);
        $this->app->singleton(PasswordForgotContract::class, PasswordForgotAction::class);
        $this->app->singleton(PasswordResetContract::class, PasswordResetAction::class);
        $this->app->singleton(ProfileUpdareContract::class, ProfileUpdateAction::class);
        $this->app->singleton(UserDeleteContract::class, UserDeleteAction::class);
    }
}