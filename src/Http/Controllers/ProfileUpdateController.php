<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\ProfileUpdareContract;

class ProfileUpdateController extends DiscreteApiController
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileUpdareContract::class)->handle($request->user(), $request->all());
    }
}
