<?php

/** @noinspection PhpParamsInspection, PhpUndefinedNamespaceInspection, PhpUndefinedClassInspection */

namespace MakeIT\DiscreteApiBase\Actions;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordResetAction extends \MakeIT\DiscreteApiBase\Contracts\PasswordResetContract
{
    public function handle(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'string', 'max:255', 'exists:users'],
                'password' => ['required', 'string', 'confirmed', (new Password(8))->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'token' => 'required',
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 400);
            }
            //
            $status = PasswordBroker::reset($input, function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            });

            //
            return $status === PasswordBroker::PASSWORD_RESET
                ? response()->json(null, 204)
                : response()->json(['error' => __($status)]);
        }

        return null;
    }
}
