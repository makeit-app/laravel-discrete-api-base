<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\ProfileUpdateContract;

class ProfileUpdateController extends DiscreteApiController
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileUpdateContract::class)->handle($request->user(), $request->all());
    }
}
