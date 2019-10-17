<?php

namespace App\Http\Middleware;

use Closure;
use RCAuth;
use App\Students;
use App\CompletedSections;

class PopulateDependencies
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
      $rcid = RCAuth::user()->rcid;
      $student = Students::where("RCID", $rcid)->first();

      if (empty($student)) {
        // ASSERT: Student does not currently exist
        // Creating the student
        $student = new Students;
        $student->RCID       = $rcid;
        $student->created_by = $rcid;
        $student->updated_by = $rcid;
        $student->save();

        // Create the completed section table
        $new_completion = new CompletedSections;
        $new_completion->fkey_rcid  = $rcid;
        $new_completion->created_by = $rcid;
        $new_completion->updated_by = $rcid;
        $new_completion->save();
      }

      app()->instance(Students::class, $student);

      return $next($request);
    }
}
