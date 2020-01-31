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
      $student = Students::find($rcid);

      if (empty($student->created_by)) {
        // ASSERT: Student does not currently exist
        // Creating the student
        \DB::statement("EXEC copy_ods_student_and_race_to_student_forms ?", [$rcid]);
        $student = Students::find($rcid);

        // COPY: ODS emergency => Local Emergency
        \DB::statement("EXEC copy_ods_emergency_to_student_forms ?", [$rcid]);
        \DB::statement("EXEC copy_ods_health_concerns_to_student_forms ?", [$rcid]);

        //Locking FTW
        $file = fopen(storage_path("lock"), "w");
        if(flock($file, LOCK_EX)) {
          \DB::statement("EXEC copy_ods_guardian_to_student_forms ?", [$rcid]);
          \DB::statement("EXEC copy_ods_employment_to_student_forms ?", [$rcid]);
          flock($file, LOCK_UN);
        }
        fclose($file);
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
