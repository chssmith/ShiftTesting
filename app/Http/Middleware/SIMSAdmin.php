<?php

namespace App\Http\Middleware;

use Closure;
use RCAuth;
use \App\User;
use Redirect;

class SIMSAdmin
{
    CONST token = "SIMSAdmin";
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$returnRoute = Redirect::to('login')->with('returnURL', $request->fullUrl());
      if((RCAuth::check(SIMSAdmin::token) || RCAuth::attempt(SIMSAdmin::token))){

    		$rcid = RCAuth::user()->rcid;
    		$user = User::where('RCID', $rcid)->first();

        app()->instance(User::class, $user);

    		if(!empty($user)){
    			$returnRoute = $next($request);
    		}
    	}

    	return ($returnRoute);
    }
}
