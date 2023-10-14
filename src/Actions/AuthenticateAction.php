<?php

namespace MakeIT\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticateAction extends \MakeIT\DiscreteApi\Base\Contracts\AuthenticateContract
{
    public function handle(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required', 'string'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 400);
            }
            //
            $User = User::where('email', $input['email'])->first();
            if (! is_null($User) && Hash::check($input['password'], $User->password)) {
                $User->tokens()->where('name', $User->email)->where('client_type', 'web')->delete();

                return response()->json(['token' => $User->createToken($User->email, ['*'], 'web')->plainTextToken], 201);
            }

            return response()->json(null, 204);
        }

        return null;
    }
}
