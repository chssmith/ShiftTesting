<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use RCAuth;
use Redirect;

class ForceLogin
{
    /**
     *  Handle an Incoming Request
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $returnRoute = Redirect::to('login')->with('returnURL', $request->fullUrl());

        if ((RCAuth::check() || RCAuth::attempt())) {
            $rcid = RCAuth::user()->rcid;
            $user = User::where('RCID', $rcid)->first();
            app()->instance(User::class, $user);

            if (! empty($user)) {
                $returnRoute = $next($request);
            }
        }

        return $returnRoute;
    }
}
