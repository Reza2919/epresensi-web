<?php

namespace App\Http\Middleware;

use Closure;

class Login
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
        if(empty(session('userdata')) || empty(session('token'))){
            $token = $request->bearerToken();
            if($token){
                $tokenParts = explode(".", $token);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtPayload = json_decode($tokenPayload);
                if(@$jwtPayload->id_pegawai){
                    $session['userdata'] = $jwtPayload;
                }else{
                    $session['userdata'] = $jwtPayload->user;
                }
                $session['token'] = $token;
                session($session);
                return $next($request);
            }else{
                return redirect('/login');
            }
        }
        return $next($request);
    }
}
