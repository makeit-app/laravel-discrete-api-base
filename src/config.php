<?php

return [
    /**
     * Frontend settings
     */
    'frontend_domain' => env('APP_FRONTEND_DOMAIN'),
    'frontend_url' => env('APP_FRONTEND_URL'),
    'frontend_login_url' => env('APP_FRONTEND_LOGIN_URL'),
    'frontend_dashboard_url' => env('APP_FRONTEND_DASHBOARD_URL'),
    /**
     * Roles
     */
    'default_role' => 'user',
    'super_role' => 'super',
    'admin_role' => 'admin',
    'support_role' => 'support',
    'user_role' => 'user',
    /**
     * What to use as login username
     */
    'username' => 'email',
    /**
     * Which features to use
     */
    'features' => [
        'email_verification' => true,
        'user_delete' => true,
    ],
    /**
     * What to use as route namespace
     * (where to look for controllers)
     * "package" -> look for package controllers to
     *      \MakeIT\DiscreteApi\Base\Http\Controllers
     * "app" -> look for application controllers placement
     *      \App\Http\Controllers\DiscreteApi\Base
     */
    'route_namespace' => 'package', // or "app"
    /**
     * Policies. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'policiesToRegister' => [
        \App\Models\User::class                        => \MakeIT\DiscreteApi\Base\Policies\UserPolicy::class,
        \MakeIT\DiscreteApi\Base\Models\Role::class    => \MakeIT\DiscreteApi\Base\Policies\RolePolicy::class,
        \MakeIT\DiscreteApi\Base\Models\Profile::class => \MakeIT\DiscreteApi\Base\Policies\ProfilePolicy::class,
    ],
    /**
     * Observers. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'observersToRegister' => [
        \App\Models\User::class                        => \MakeIT\DiscreteApi\Base\Observers\UserObserver::class,
        \MakeIT\DiscreteApi\Base\Models\Role::class    => \MakeIT\DiscreteApi\Base\Observers\RoleObserver::class,
        \MakeIT\DiscreteApi\Base\Models\Profile::class => \MakeIT\DiscreteApi\Base\Observers\ProfileObserver::class,
    ],
    /**
     * Namespaces for class generator
     */
    'namespaces' => [
        'app' => '\\App\\', // `app/` directory
        'package' => '\\MakeIT\\DiscreteApi\\Base\\', // `package/src/` directory
    ],
];
