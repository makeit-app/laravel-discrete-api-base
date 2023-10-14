<?php

namespace MakeIT\DiscreteApi\Base\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class ProfileUpdareContract
{
    abstract public function handle(User $User, array $input): ?JsonResponse;
}
