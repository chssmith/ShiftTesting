<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Redirect;
use RCAuth;

class ForceAdmin
{
    CONST token = "RSIAdmin";

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
        if((RCAuth::check(self::token) || RCAuth::attempt(self::token))){

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
