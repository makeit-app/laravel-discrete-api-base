<?php

/** @noinspection PhpUndefinedNamespaceInspection, PhpUndefinedClassInspection */

namespace MakeIT\DiscreteApiBase\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProfileAvatarUpdateAction extends \MakeIT\DiscreteApiBase\Contracts\ProfileAvatarUpdateContract
{
    public function handle(User $User, array $input = []): ?JsonResponse
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'avatar' => ['required', 'image', 'nullable', 'mimes:jpg,jpeg,png,gif', 'max:1024']
            ]);
            //
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 400);
            } else {
                $Profile = $User->profile;
                if (!is_null($Profile)) {
                    $Profile->updateProfileAvatar(request()->file('avatar'));
                }
                $User->load(['profile']);
                return response()->json($User->toArray(), 200);
            }
        }
        return null;
    }
}
