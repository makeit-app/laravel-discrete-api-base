<?php

namespace MakeIT\DiscreteApi\Base\Contracts;

use Illuminate\Http\JsonResponse;

abstract class PasswordResetContract
{
    abstract public function handle(array $input): ?JsonResponse;
}
