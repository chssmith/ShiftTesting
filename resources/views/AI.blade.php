@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
  Financial Acceptance Statement
@endsection

@section("stylesheets")
	@parent
  <style>
    ul {
      list-style: circle;
      margin-left: 30px;
    }
    ul > li {
      margin: 15px;
    }
    .panel-body {
      font-size: 14pt;
    }
    .list-group {
      display: grid;
      grid-template-rows: repeat(6, 1fr);
    }
    .pretty label {
      white-space: normal;
    }
    .pretty {
      width: 90%;
    }
    .pretty .state label {
      display: grid;
      grid-template-areas: "checkbox label";
      grid-template-columns: 40px 1fr;
    }
    .pretty .state label:before {
      grid-area: checkbox;
    }
    .pretty .state label > span {
      grid-area: label;
      text-indent: 0px;
    }
    a {
      text-decoration: underline;
    }

  </style>
@endsection

@section("content")
  <div class="panel">
    <div class="panel-body">
      <h2>
        Academic Integrity <span class="small"><a href="https://www.roanoke.edu/academicintegrity" target="_blank">Roanoke College Academic Integrity Handbook</a></span>
      </h2>
      <p>
        Roanoke College is an academic community guided by the principles of
        honesty, respect, and personal responsibility.  As a member of the
        community, I agree to support and uphold these principles.
      </p>
      <p>
        Violations of academic integrity include:
      </p>
      <ul>
        <li>
          Cheating;
        </li>
        <li>
          Unauthorized collaboration;
        </li>
        <li>
          Plagiarism (including paper drafts);
        </li>
        <li>
          Unauthorized use of electronic devices
        </li>
      </ul>

      <p>
        I acknowledge that students have a responsibility to ensure the integrity of their own work, and ignorance of the academic integrity system is not an acceptable excuse for violations.
      </p>
      <p>
        I acknowledge that those persons dire affected by my behavior may be notified regarding the outcome of any disciplinary action taken against me.
      </p>
      <p>
        I acknowledge that continued enrollment at Roanoke College indicates my willingness to follow the rules of the academic integrity system.
      </p>
      <p>
        I affirm that I will work fully with the faculty, administration, and other students to uphold and support the academic integrity system.
      </p>

      <h2>
        Student Code of Conduct <span class="small"><a href="https://www.roanoke.edu/studentconduct" target="_blank">The Roanoke College Student Code of Conduct</a></span>
      </h2>
      <p>
        This mission includes providing a safe, special living/learning
        environment in which students can pursue their education without undue
        interruption or distraction.  In order for this to be possible, students
        are expected to:
      </p>
      <ul>
        <li>
          Be honest
        </li>
        <li>
          Respect the rights and property of other member of the College and the local community
        </li>
        <li>
          Conduct themselves in a responsible manner
        </li>
      </ul>

      <p>
        For many students, the college experience means an opportunity for
        increased freedom.  Along with this increased freedom comes the
        obligation of handling it responsibly.  Students are expected to behave
        responsibly and will be held accountable for their actions.
      </p>

    </div>
    <div class="panel-footer" style="background-color: white">
      <form action="{{ action ("StudentInformationController@completeAcademicIntegrityStatement") }}" method="POST">
        {{ csrf_field() }}
        <div>
          <div class="pretty p-default">
            <input type="checkbox" name="acknowledge" value=true id="acknowledge" @if($student->ai_and_student_conduct) checked @endif />
            <div class="state p-primary">
              <label>
                <span>
                  By checking this box, I acknowledge that I have read and
                  understand the information, policies, and procedures for Academic
                  Integrity and Student Conduct.
                </span>
              </label>
            </div>
          </div>
        </div>
        <div style="text-align: right">
          <button type="submit" class="btn btn-secondary">Accept</button>
        </div>
      </form>
    </div>
  </div>


@endsection