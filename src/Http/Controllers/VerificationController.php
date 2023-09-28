<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

/**
 * {@inheritDoc}
 */
class VerificationController extends DiscreteApiController
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json(null, 204);
    }
}
