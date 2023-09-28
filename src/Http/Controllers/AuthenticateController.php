<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\AuthenticateContract;

/**
 * {@inheritDoc}
 */
class AuthenticateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(AuthenticateContract::class)->handle($request->only(['email', 'password']));
    }
}
