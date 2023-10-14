<?php

namespace MakeIT\DiscreteApi\Base\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class LogoutContract
{
    abstract public function handle(User $User): ?JsonResponse;
}
