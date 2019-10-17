<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use RCAuth;
use App\User;

class ForceLogin
{
    /**
     *  Handle an Incoming Request
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next){
    	$returnRoute = Redirect::to('login')->with('returnURL', $request->fullUrl());

    	if((RCAuth::check() || RCAuth::attempt())){

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
