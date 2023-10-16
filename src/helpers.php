<?php

if (! function_exists('compute_discreteapi_base_namespace')) {
    /**
     * Returns the appropriate Namespace for the api, as specified in the configuration
     */
    function compute_discreteapi_base_namespace(): string
    {
        if (config('discreteapibase.route_namespace') === 'app') {
            return config('discreteapibase.namespaces.app', '\\App\\');
        }

        return config('discreteapibase.namespaces.package', '\\MakeIT\\DiscreteApi\\Base\\');
    }
}
if (! function_exists('compute_discreteapi_base_route_namespace')) {
    /**
     * Returns the appropriate Route Namespace for the api, as specified in the configuration
     */
    function compute_route_discreteapi_base_namespace(): string
    {
        switch (config('discreteapibase.route_namespace')) {
            case 'app':
                return compute_discreteapi_base_namespace().'Http\\Controllers\\DiscreteApi\\Base';
                break;
            default:
            case 'package':
                return compute_discreteapi_base_namespace().'Http\\Controllers';
        }
    }
}
if (! function_exists('discreteapibase_package_path')) {
    function discreteapibase_package_path(): string
    {
        return __DIR__;
    }
}
