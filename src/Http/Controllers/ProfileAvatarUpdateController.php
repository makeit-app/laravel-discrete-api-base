<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApiBase\Contracts\ProfileAvatarUpdateContract;

class ProfileAvatarUpdateController extends DiscreteApiController
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(app(ProfileAvatarUpdateContract::class)->handle($request->user(), $request->only(['avatar'])));
    }
}
