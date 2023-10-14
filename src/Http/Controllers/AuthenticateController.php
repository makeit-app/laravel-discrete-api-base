<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\AuthenticateContract;

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
