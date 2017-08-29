<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class AuthorizeStudent
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::user()->isStudent()) {
            throw new AuthorizationException('Unauthorized.');
        }

        return $next($request);
    }
}
