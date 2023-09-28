<?php

namespace MakeIT\DiscreteApiBase\Contracts;

use Illuminate\Http\JsonResponse;

abstract class RegisterContract
{
    abstract public function handle(array $input): ?JsonResponse;
}
