<?php

namespace MakeIT\DiscreteApi\Base\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use MakeIT\DiscreteApi\Base\Console\Commands\InstallDiscreteApiBaseCommand;
use MakeIT\DiscreteApi\Base\Contracts\AuthenticateContract;
use MakeIT\DiscreteApi\Base\Contracts\LogoutContract;
use MakeIT\DiscreteApi\Base\Contracts\PasswordForgotContract;
use MakeIT\DiscreteApi\Base\Contracts\PasswordResetContract;
use MakeIT\DiscreteApi\Base\Contracts\RegisterContract;
use MakeIT\DiscreteApi\Base\Contracts\UserDeleteContract;
use MakeIT\DiscreteApi\Base\Contracts\UserForceDeleteContract;
use MakeIT\DiscreteApi\Base\Helpers\DiscreteApiHelpers;

class DiscreteApiBaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'discreteapibase');
    }

    /**
     * Bootstrap any application services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'discreteapibase');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../lang');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //
        $this->configurePersonalAccessToken();
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
        Sanctum::usePersonalAccessTokenModel(
            config('discreteapibase.route_namespace') === 'app'
                ? DiscreteApiHelpers::compute_namespace(config('discreteapibase')) . 'Models\\DiscreteApi\\Base\\PersonalAccessToken'
                : DiscreteApiHelpers::compute_namespace(config('discreteapibase')) . 'Models\\PersonalAccessToken'
        );
    }

    /**
     * Configures a poblishes
     */
    protected function configurePublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([realpath(__DIR__ . '/../../database/migrations') => base_path('database/migrations')], 'migrations');
            $this->publishes([realpath(__DIR__ . '/../../lang') => lang_path('vendor/discreteapibase')], 'lang');
            $this->publishes([realpath(__DIR__ . '/../../stubs/User.php') => app_path('/Models/User.php')], 'user-model');
        }
    }

    /**
     * Configure the commands offered by the application.
     */
    protected function configureCommands(): void
    {
        if (app()->runningInConsole()) {
            $this->commands([
                InstallDiscreteApiBaseCommand::class,
            ]);
        }
    }

    /**
     * Configure the routes offered by the application.
     */
    protected function configureRoutes(): void
    {
        $parsed = parse_url(config('app.url', 'http://localhost'));
        $domain = $parsed['host'];
        unset($parsed);
        $ns = DiscreteApiHelpers::compute_namespace(config('discreteapibase'));
        Route::domain($domain)
            ->middleware(['api'])
            ->namespace(
                config('discreteapibase.route_namespace') === 'app'
                    ? $ns . 'Http\\Controllers\\DiscreteApi\\Base'
                    : $ns . 'Http\\Controllers'
            )
            ->prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes.php');
            });
    }

    /**
     * Configure Policies
     */
    protected function configurePolicies(): void
    {
        if (config('user_roles.policiesToRegister', [])) {
            foreach (config('user_roles.policiesToRegister', []) as $model => $policy) {
                Gate::policy($model, $policy);
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
        $actions_namespace = config('discreteapibase.route_namespace') === 'app'
            ? DiscreteApiHelpers::compute_namespace(config('discreteapibase')) . 'Actions\\DiscreteApi\\Base\\'
            : DiscreteApiHelpers::compute_namespace(config('discreteapibase')) . 'Actions\\';
        $this->app->singleton(RegisterContract::class, $actions_namespace . 'RegisterAction');
        $this->app->singleton(AuthenticateContract::class, $actions_namespace . 'AuthenticateAction');
        $this->app->singleton(LogoutContract::class, $actions_namespace . 'LogoutAction');
        $this->app->singleton(PasswordForgotContract::class, $actions_namespace . 'PasswordForgotAction');
        $this->app->singleton(PasswordResetContract::class, $actions_namespace . 'PasswordResetAction');
        $this->app->singleton(UserDeleteContract::class, $actions_namespace . 'UserDeleteAction');
        $this->app->singleton(UserForceDeleteContract::class, $actions_namespace . 'UserForceDeleteAction');
    }
}
