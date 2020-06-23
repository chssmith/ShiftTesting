@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
Roanoke College's Crush COVID-19 Pledge
@endsection

@section("stylesheets")
	@parent
  <link rel="stylesheet" type="text/css" href="{{ asset("css/checkbox_form.css") }}" />

  <style>
    li {
      list-style-type: none;
    }
    li::before {
      font-family: "Font Awesome 5 Pro";
      content: "\f00c";
    }
  </style>
@endsection

@section("content")
  <div class="panel">
    <div class="panel-body">
      <p>
        Roanoke College is committed to providing the safest environment
        possible for the prevention and spread of COVID-19 while still meeting
        our mission of educating our students.  We are following guidance from
        the Centers for Disease Control (CDC), American College Health
        Association (ACHA), and The Virginia Department of Health (VDH).
      </p>

      <p>
        Safety is everyone’s responsibility, and the COVID-19 pandemic has shown
        us how crucial it is for all Maroons to make a commitment to safety to
        lessen the likelihood of an uncontrollable outbreak occurring on campus.
        The entire college community needs to do their part to make safety a
        personal priority.  Your electronic signature indicates a commitment to
        these practices.
      </p>

      <ul>
        <li>
          I will follow the campus guidelines designed to protect against COVID-19.
        </li>
        <li>
        	I will follow the Face Covering Policy of the college and maintain social distance when not wearing a facial covering to protect my campus.
        </li>
        <li>
        	When I first notice COVID-19 symptoms, I will immediately self-isolate and contact the appropriate campus authority.
        </li>
        <li>
        	I will take responsibility for my health and the health of my campus.
        </li>
        <li>
        	I will practice good handwashing and hygiene skills.
        </li>
        <li>
        	I will be flexible in my working and learning environments and put forth my best effort.
        </li>
      </ul>
      <p class="center">
        A Healthy Campus is in Our Hands!
      </p>
    </div>

    <div class="panel-footer" style="background-color: white">
      <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
          <form action="{{ action ("StudentInformationController@completeCovidForm") }}" method="POST">
            {{ csrf_field() }}
            <div>
              <div class='cb'>
                <input type="checkbox" name="acknowledge" value=true id="acknowledge" @if($student->covid_acceptance) checked @endif />
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
