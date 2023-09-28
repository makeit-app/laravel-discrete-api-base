<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\RegisterContract;

/**
 * {@inheritDoc}
 */
class RegisterController extends DiscreteApiController
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(RegisterContract::class)->handle($request->only(['email', 'password', 'password_confirmation']));
    }
}
