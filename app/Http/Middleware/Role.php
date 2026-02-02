<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        if (empty(session('userdata')) || empty(session('token')))
            return redirect('login');

        $user = session('userdata');
        foreach($roles as $role) {
            if($user->role == $role)
                return $next($request);                
        }
        return abort(403);
    }
}
