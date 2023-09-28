<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\PasswordResetContract;

/**
 * {@inheritDoc}
 */
class PasswordResetController extends DiscreteApiController
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(PasswordResetContract::class)->handle(
            $request->only([
                'email',
                'password',
                'password_confirmation',
                'token',
            ])
        );
    }
}
