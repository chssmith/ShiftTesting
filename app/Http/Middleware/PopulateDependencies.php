<?php

namespace App\Http\Middleware;

use Closure;
use RCAuth;
use App\Students;
use App\CompletedSections;

use Illuminate\Support\Facades\Cache; //For atomic locks

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
      $rcid    = RCAuth::user()->rcid;
      $student = Students::firstOrNew(["RCID" => $rcid]);

      if (empty($student->created_by)) {
        // ASSERT: Student does not currently exist
        // Creating the student
        $student->created_by = $rcid;
        $student->updated_by = $rcid;
        $student->save();

        // COPY: ODS emergency => Local Emergency
        \DB::statement("EXEC copy_ods_emergency_to_student_forms ?", [$rcid]);

        Cache::lock("copy_guardians")->get(function () {
          \DB::statement("EXEC copy_ods_guardian_to_student_forms ?", [$rcid]);
          \DB::statement("EXEC copy_ods_employment_to_student_forms ?", [$rcid]);          
        });
      }

      $new_completion = CompletedSections::firstOrNew(["fkey_rcid" => $rcid]);
      if (empty($new_completion->created_by)) {
        // Create the completed section table
        $new_completion->created_by = $rcid;
        $new_completion->updated_by = $rcid;
        $new_completion->save();
      }
      app()->instance(Students::class, $student);
      app()->instance(CompletedSections::class, $new_completion);

      return $next($request);
    }
}
