<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\ProfileUpdareContract;

/**
 * {@inheritDoc}
 */
class ProfileUpdateController extends DiscreteApiController
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileUpdareContract::class)->handle($request->user(), $request->all());
    }
}
