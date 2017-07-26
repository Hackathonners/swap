<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class AuthorizeAdministrator
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
        $this->checkAdmin();

        return $next($request);
    }

    /**
     * Determine if the user is an admin.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function checkAdmin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return;
        }

        throw new AuthorizationException('Unauthorized.');
    }
}
