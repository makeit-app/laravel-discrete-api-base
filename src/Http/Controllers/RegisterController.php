<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\RegisterContract;

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
