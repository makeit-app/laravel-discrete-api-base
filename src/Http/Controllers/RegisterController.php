<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\RegisterContract;

class RegisterController extends DiscreteApiController
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(RegisterContract::class)->handle($request->only(['email', 'password', 'password_confirmation']));
    }
}
