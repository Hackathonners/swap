<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeEnrollmentActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $redirect = $this->checkEnrollmentPeriod();

        return $redirect ?? $next($request);
    }

    /**
     * Determine if the enrollments period is active.
     *
     * @throws \Illuminate\Auth\AuthenticationException
     *
     * @return mixed
     */
    protected function checkEnrollmentPeriod()
    {
        if (app('settings')->withinEnrollmentPeriod()) {
            return;
        }

        flash('The enrollments period is closed. You are not allowed to perform this action.')->error();

        return redirect()->route('home');
    }
}
