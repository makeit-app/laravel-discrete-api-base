<?php

namespace MakeIT\DiscreteApi\Base\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class UserForceDeleteContract
{
    abstract public function handle(User $Admin, User $User): ?JsonResponse;
}
