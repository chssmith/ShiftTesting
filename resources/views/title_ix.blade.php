@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
  Financial Acceptance Statement
@endsection

@section("stylesheets")
	@parent
  <link rel="stylesheet" type="text/css" href="{{ asset("css/checkbox_form.css") }}" />
@endsection

@section("content")
  <div class="panel">
    <div class="panel-body">
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
    </div>

    <div class="panel-footer" style="background-color: white">
      <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
          <form action="{{ action ("StudentInformationController@completeTitleIXAcceptance") }}" method="POST">
            {{ csrf_field() }}
            <div>
              <div class='cb'>
                <input type="checkbox" name="acknowledge" value=true id="acknowledge" @if($student->title_ix_acceptance) checked @endif />
                <label for='acknowledge'>
                    By checking this box, I acknowledge, understand, and accept these terms.
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
