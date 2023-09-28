<?php

namespace MakeIT\DiscreteApiBase\Contracts;

use Illuminate\Http\JsonResponse;

abstract class AuthenticateContract
{
    abstract public function handle(array $input): ?JsonResponse;
}
