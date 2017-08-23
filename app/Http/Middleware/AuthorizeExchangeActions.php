<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeExchangeActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
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
     * @return null|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function checkEnrollmentPeriod()
    {
        if (app('settings')->withinExchangePeriod()) {
            return;
        }

        flash('The exchanges period is closed. You are not allowed to perform this action.')->error();

        return redirect()->route('home');
    }
}
