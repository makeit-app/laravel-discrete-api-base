<?php

/** @noinspection PhpUndefinedNamespaceInspection, PhpUndefinedClassInspection */

namespace MakeIT\DiscreteApiBase\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class ProfileAvatarDeleteContract
{
    abstract public function handle(User $User, array $input = []): ?JsonResponse;
}
