<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfUnconfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! auth()->user()->verified) {
            return response(view('auth.unconfirmed'), 403);
        }

        return $next($request);
    }
}
