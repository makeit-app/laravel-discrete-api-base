<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\UserDeleteContract;

class UserDeleteController extends DiscreteApiController
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return app(UserDeleteContract::class)->handle($request->user(), $request->only(['current_password']));
    }
}
