<?php

namespace MakeIT\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterAction extends \MakeIT\DiscreteApi\Base\Contracts\RegisterContract
{
    public function handle(array $input): ?JsonResponse
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'string', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', (new Password(8))->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User = User::create([
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            event(new Registered($User));

            return response()->json($User->toArray());
        }

        return null;
    }
}
