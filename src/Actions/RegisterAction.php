<?php

/** @noinspection PhpUndefinedNamespaceInspection, PhpUndefinedClassInspection */

namespace MakeIT\DiscreteApiBase\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterAction extends \MakeIT\DiscreteApiBase\Contracts\RegisterContract
{
    public function handle(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'string', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', (new Password(8))->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 400);
            }
            $User = User::create([
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
            $User->load(['profile']);

            return response()->json($User->toArray());
        }

        return null;
    }
}
