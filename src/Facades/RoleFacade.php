<?php

namespace MakeIT\DiscreteApi\Base\Facades;

use Illuminate\Support\Facades\Facade;

class RoleFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'role';
    }
}
