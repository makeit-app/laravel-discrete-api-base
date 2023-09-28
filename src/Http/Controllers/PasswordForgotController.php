<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\PasswordForgotContract;

/**
 * {@inheritDoc}
 */
class PasswordForgotController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(PasswordForgotContract::class)->handle($request->only(['email']));
    }
}
