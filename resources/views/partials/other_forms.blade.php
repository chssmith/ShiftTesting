<div class="list-group">
  <a href="{{ action("StudentInformationController@getAPExams") }}" class="list-group-item">
    Advanced Placement Examinations
  </a>
  <a href="{{ action("StudentInformationController@getIBCourses") }}" class="list-group-item">
    International Baccalaureate Examinations
  </a>
  <a href="{{ action("StudentInformationController@showAcademicIntegrityStatement") }}" class="list-group-item">
    Academic Integrity and Student Code of Conduct Statement @if($student->ai_and_student_conduct) @include("partials.complete_badge") @endif
  </a>
  @include("partials.collapsing_panel", ["postfix" => "AlcoholEDU",
                                         "title" => "AlcoholEdu and Haven",
                                         "content" => "As part of our comprehensive
                                          prevention program for new students,
                                          Roanoke College requires you to
                                          complete AlcoholEdu and Sexual Assault
                                          Prevention for Undergraduates.  These
                                          online courses will empower you to make
                                          well-informed decisions about issues
                                          that affect your college years and
                                          beyond.  You will receive course
                                          information and instructions in your
                                          Roanoke College email by the end of
                                          July.  These courses must be completed
                                          prior to arriving on campus."
                                       ])
  <a href="{{ action("StudentInformationController@showFinancialAcceptance") }}" class="list-group-item">
    Financial Acceptance @if($student->financial_acceptance) @include("partials.complete_badge") @endif
  </a>
</div>
