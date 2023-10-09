<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationResendController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => [
                __('Verification link sent!'),
            ],
        ]);
    }
}
