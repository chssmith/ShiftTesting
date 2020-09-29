<?php

namespace App\Http\Controllers\StudentForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\GenericAddress;
use App\CompletedSections;
use App\Students;
use App\User;


abstract class SectionController extends Controller
{
  public function __construct(){
      $this->middleware("force_login");
			$this->middleware("populate_dependencies");
	}

  /**
   * Get a list of messages of missing data for this section.
   *
   * @param Collection $requirements, An associative array mapping requirement to the message to display if failed.
   * @param Collection $scope, An associative array mapping variables to their value, to setup a local scope for eval.
   * @return Collection A list of messages pertaining to missing data.
   **/
  protected static function getMessages ($requirements, $scope) {
    foreach ($scope as $var => $value) {
      eval(sprintf('%s = $value;', $var));
    }

    $messages = collect();
    foreach ($requirements as $requirement => $message) {
      eval(sprintf('$result = %s;', $requirement));
      if (!$result) {
        $messages[] = $message;
      }
    }

    return $messages;
  }

  /**
   * Display the form necessary for this section
   *
   * @return View The view of this form section to display
   **/
  public abstract function show (Students $student, User $vpb_user, CompletedSections $completed_sections);

  /**
   * Store the user submission for this section.
   *
   * @return Redirect A redirect to the next page in the form sequence
   **/
  public abstract function store (Request $request, Students $student, CompletedSections $completed_sections);

  /**
   * Get details pertaining to missing data for this section.
   *
   * @param Students $student, The student to check the missing information for.
   * @return Collection List of strings pertaining to missing data.
   **/
  public abstract function getMissingInformation (Students $student);
}
