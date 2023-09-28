<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\UserDeleteContract;

/**
 * {@inheritDoc}
 */
class UserDeleteController extends DiscreteApiController
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(UserDeleteContract::class)->handle($request->user(), $request->only(['current_password']));
    }
}
