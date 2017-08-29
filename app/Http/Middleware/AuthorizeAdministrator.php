<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorizeAdministrator
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
        if (! Auth::user()->isAdmin()) {
            abort(404);
        }

        return $next($request);
    }
}
