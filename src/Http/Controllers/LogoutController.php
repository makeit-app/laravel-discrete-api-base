<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\LogoutContract;

/**
 * {@inheritDoc}
 */
class LogoutController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(LogoutContract::class)->handle($request->user());
    }
}
