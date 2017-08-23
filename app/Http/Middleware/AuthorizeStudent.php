<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class AuthorizeStudent
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
        $this->checkStudent();

        return $next($request);
    }

    /**
     * Determine if the user is a student.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function checkStudent()
    {
        if (! auth()->check() || ! auth()->user()->isStudent()) {
            throw new AuthorizationException('Unauthorized.');
        }

    }
}
