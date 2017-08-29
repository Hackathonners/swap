<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeExchangeActions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! app('settings')->withinExchangePeriod()) {
            flash('The exchanges period is closed. You are not allowed to perform this action.')->error();

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
