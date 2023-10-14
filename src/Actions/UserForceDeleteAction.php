<?php

namespace MakeIT\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserForceDeleteAction extends \MakeIT\DiscreteApi\Base\Contracts\UserForceDeleteContract
{
    public function handle(User $Admin, User $User): ?JsonResponse
    {
        if (!app()->runningInConsole()) {
            //doublecheck !!!
            if (!$Admin->hasRole(['super'])) {
                return response()->json(null, 404);
            }
            $User->tokens()->delete();
            $User->forceDelete();
            return response()->json(null, 204);
        }
        return null;
    }
}
