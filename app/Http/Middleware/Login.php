<?php

namespace App\Http\Middleware;

use Closure;

class Login
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('userdata')) {
            return redirect('/login');
        }

        return $next($request);
    }
}