<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DiscreteApiController extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
