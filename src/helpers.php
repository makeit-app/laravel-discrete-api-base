<?php

if (! function_exists('compute_namespace')) {
    /**
     * Returns the appropriate Namespace for the api, as specified in the configuration
     */
    function compute_namespace(): string
    {
        if (config('discreteapibase.route_namespace') === 'app') {
            return config('discreteapibase.namespaces.app', '\\App\\');
        }

        return config('discreteapibase.namespaces.package', '\\MakeIT\\DiscreteApiBase\\');
    }
}
if (! function_exists('compute_route_namespace')) {
    /**
     * Returns the appropriate Route Namespace for the api, as specified in the configuration
     */
    function compute_route_namespace(): string
    {
        switch (config('discreteapibase.route_namespace')) {
            case 'app':
                return compute_namespace().'Http\\Controllers\\DiscreteApiBase';
                break;
            default:
            case 'package':
                return compute_namespace().'Http\\Controllers';
        }
    }
}
if (! function_exists('discreteapibase_package_path')) {
    function discreteapibase_package_path(): string
    {
        return __DIR__;
    }
}
