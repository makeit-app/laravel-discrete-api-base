<?php

namespace MakeIT\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class LogoutAction extends \MakeIT\DiscreteApi\Base\Contracts\LogoutContract
{
    public function handle(User $User): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $User->tokens()->delete();

            return response()->json(null, 204);
        }

        return null;
    }
}
