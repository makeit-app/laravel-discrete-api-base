<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\LogoutContract;

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
