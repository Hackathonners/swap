<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class AuthorizeAdministrator
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
        $this->checkAdmin();

        return $next($request);
    }

    /**
     * Determine if the user is an admin.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function checkAdmin()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            throw new AuthorizationException('Unauthorized.');
        }
    }
}
