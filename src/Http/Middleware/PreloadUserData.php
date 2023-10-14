<?php

namespace MakeIT\DiscreteApi\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreloadUserData
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $request->user()->load([
                'profile',
            ]);
        }

        return $next($request);
    }
}
