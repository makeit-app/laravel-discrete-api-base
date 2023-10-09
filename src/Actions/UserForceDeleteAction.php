<?php

/** @noinspection PhpUndefinedNamespaceInspection, PhpUndefinedClassInspection */

namespace MakeIT\DiscreteApiBase\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use MakeIT\DiscreteApiBase\Contracts\UserForceDeleteContract;

class UserForceDeleteAction extends UserForceDeleteContract
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
