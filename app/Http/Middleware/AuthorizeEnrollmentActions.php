<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeEnrollmentActions
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
        if (! app('settings')->withinEnrollmentPeriod()) {
            flash('The enrollments period is closed. You are not allowed to perform this action.')->error();

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
