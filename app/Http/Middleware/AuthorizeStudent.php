<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class AuthorizeStudent
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
         $this->checkStudent();

         return $next($request);
     }

     /**
      * Determine if the user is a student.
      *
      * @return void
      *
      * @throws \Illuminate\Auth\AuthenticationException
      */
     protected function checkStudent()
     {
         if (Auth::check() && Auth::user()->isStudent()) {
             return;
         }

         throw new AuthorizationException('Unauthorized.');
     }
}
