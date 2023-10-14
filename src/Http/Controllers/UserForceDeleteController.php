<?php

namespace MakeIT\DiscreteApi\Base\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MakeIT\DiscreteApi\Base\Contracts\UserForceDeleteContract;

class UserForceDeleteController extends DiscreteApiController
{
    /**
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(Request $request, string $user_id): JsonResponse
    {
        // TODO: make able to work without MakeIT\UserRoles package
        $Admin = $request->user();
        if (!$Admin->hasRole(['super'])) {
            abort(404);
        }
        $User = User::findOrFail($user_id);
        return app(UserForceDeleteContract::class)->handle($Admin, $User);
    }
}
