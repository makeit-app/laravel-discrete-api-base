<?php

/** @noinspection PhpUndefinedNamespaceInspection, PhpUndefinedClassInspection */

namespace MakeIT\DiscreteApiBase\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class UserForceDeleteContract
{
    abstract public function handle(User $Admin, User $User): ?JsonResponse;
}
