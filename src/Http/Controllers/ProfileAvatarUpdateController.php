<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\ProfileAvatarUpdateContract;

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
