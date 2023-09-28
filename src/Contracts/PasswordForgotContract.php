<?php

namespace MakeIT\DiscreteApiBase\Contracts;

use Illuminate\Http\JsonResponse;

abstract class PasswordForgotContract
{
    abstract public function handle(array $input): ?JsonResponse;
}
