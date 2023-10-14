<?php

namespace MakeIT\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use MakeIT\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;

class UserDeleteAction extends \MakeIT\DiscreteApi\Base\Contracts\UserDeleteContract
{
    public function handle(User $User, array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'current_password' => ['required', 'string', new MatchCurrentPasswordRule()],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 400);
            }
            $User->tokens()->delete();
            $User->delete();

            return response()->json($User->toArray(), 200);
        }

        return null;
    }
}
