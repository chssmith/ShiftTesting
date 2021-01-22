@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
  Academic Integrity and Student Conduct
@endsection

@section("stylesheets")
	@parent
  <link rel="stylesheet" type="text/css" href="{{ asset("css/checkbox_form.css") }}" />
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
        I acknowledge that those persons directly affected by my behavior may be notified regarding the outcome of any disciplinary action taken against me.
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

      <h2>
        Sexual Misconduct Policy <span class="small"><a href="https://www.roanoke.edu/documents/Student%20Affairs/Sexual%20Misconduct%20Policy.pdf" target="_blank">The Roanoke College Sexual Misconduct Policy & Procedures</a></span>
      </h2>
      <p>
        Roanoke College is committed to providing students with a positive
        living-learning environment. Sexual misconduct violates the rights,
        respect, and dignity of our community members and will not be tolerated at
        the College.  Violations of the Sexual Misconduct Policy include:
      </p>
      <ul>
        <li>
          Sexual Harassment
        </li>
        <li>
          Sexual Coercion
        </li>
        <li>
          Stalking/Cyber-Stalking
        </li>
        <li>
          Sexual Exploitation
        </li>
        <li>
          Unwelcome Sexual Contact
        </li>
        <li>
          Sexual Assault, Sexual Violence
        </li>
      </ul>
      <p>
        Once aware of a possible violation, the College will act swiftly to
        protect the safety of those involved, investigate and end any
        misconduct, prevent its recurrence, and remedy its effects.  Students
        are expected to treat others with respect and act responsibly in
        accordance with our policy.
      </p>
      <hr>
    </div>
    <div class="panel-footer" style="background-color: white">
      <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
          <form action="{{ action ("StudentInformationController@completeAcademicIntegrityStatement") }}" method="POST">
            {{ csrf_field() }}
            <div>
              <div class='cb'>
                <input type="checkbox" name="acknowledge" value=true id="acknowledge" @if($student->ai_and_student_conduct) checked @endif />
                <label for='acknowledge'>
                    By checking this box, I acknowledge that I have read and
                    understand the information, policies, and procedures for Academic
                    Integrity, Student Conduct, and Sexual Misconduct.
                    <span class="far fa-asterisk" aria-hidden="true"></span>
                </label>
              </div>
            </div>
            <div style="text-align: right">
      		    <button type="submit" class="btn btn-lg btn-success pull-right"> Accept </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


@endsection
