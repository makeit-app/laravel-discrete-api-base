<?php

namespace MakeIT\DiscreteApiBase\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
