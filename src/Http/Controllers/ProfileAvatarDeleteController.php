<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileAvatarDeleteController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $User = $request->user();
        $Profile = $User->profile;
        if(!is_null($Profile)) {
            $Profile->deleteAvatar();
        }
        $User->load(['profile']);
        return response()->json($User);
    }
}
