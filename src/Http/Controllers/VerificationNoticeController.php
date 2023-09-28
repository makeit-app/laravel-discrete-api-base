<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerificationNoticeController extends DiscreteApiController
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        return response()->json([
            'error' => [
                __('Email Verification is required to continue.'),
            ],
        ]);
    }
}
