<?php

return [
    /**
     * What to use as login username
     */
    'username' => 'email',
    /**
     * Which features to use
     */
    'features' => [
        'email_verification' => true,
        'avatars' => true,
        'user_delete' => true,
    ],
    /**
     * What to use as route namespace
     * (where to look for controllers)
     * "package" -> look for package controllers to
     *      \MakeIT\DiscreteApiBase\Http\Controllers
     * "app" -> look for application controllers placement
     *      \App\Http\Controllers\DiscreteApiBase
     */
    'route_namespace' => 'package', // or "app"
    /**
     * Policies. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'policiesToRegister' => [
    ],
    /**
     * Observers. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'observersToRegister' => [],
    /**
     * Namespaces for class generator
     */
    'namespaces' => [
        'app' => '\\App\\', // `app/` directory
        'package' => '\\MakeIT\\DiscreteApi\\Base\\', // `package/src/` directory
    ],
];
