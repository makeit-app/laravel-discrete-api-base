<?php

namespace MakeIT\DiscreteApi\Base\Contracts;

use Illuminate\Http\JsonResponse;

abstract class PasswordForgotContract
{
    abstract public function handle(array $input): ?JsonResponse;
}
