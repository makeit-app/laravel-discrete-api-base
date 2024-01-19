<?php

namespace MakeIT\DiscreteApi\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreloadUserProfileData
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (method_exists($request->user(), 'profile'))) {
            $request->user()->load([
                'profile',
            ]);
        }

        return $next($request);
    }
}
