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
  <a href="{{ action("StudentInformationController@showFinancialAcceptance") }}" class="list-group-item">
    Financial Acceptance @if($student->financial_acceptance) @include("partials.complete_badge") @endif
  </a>
  <a href="" class="list-group-item">
    Academic Achievement Form
  </a>
  <a href="" class="list-group-item">
    Advanced Standing Form
  </a>
  <a href="" class="list-group-item">
    Foreign Language Placement Test
  </a>
  <a href="" class="list-group-item">
    Quantitative Reasoning Test
  </a>
  <a href="" class="list-group-item">
    Academic Integrity Module
  </a>
  <a href="" class="list-group-item">
    Health Services Forms
  </a>
  @include("partials.collapsing_panel", ["postfix" => "AlcoholEDU",
                                         "title" => "AlcoholEdu and Haven",
                                         "content" => "You will receive course
                                          information and instructions in your
                                          Roanoke College email by the end of
                                          July."
                                       ])
   @include("partials.collapsing_panel", ["postfix" => "DiversityEDU",
                                          "title" => "DiversityEdu",
                                          "content" => "You will receive course
                                           information and instructions in your
                                           Roanoke College email by the end of
                                           July."
                                        ])
  <a href="" class="list-group-item">
    PSYCap Survey
  </a>

</div>
