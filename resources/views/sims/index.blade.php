@extends('forms_template')

@section('heading')
  Summer Orientation Registration
@endsection

@section("header")
@endsection

@section('javascript')

@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
  <style>
    #SIMS {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 20px;
    }
    .list-group-item {
      display: grid;
      grid-template-areas: "dates attendance-cap" "buttons buttons";
      grid-template-columns: 1fr 100px;
      grid-gap: 20px;
    }
    attendance-cap {
      justify-self: end;
    }
    dates {
      font-size: 18pt;
      line-height: 1.2em;
    }
    buttons {
      grid-area: buttons;
    }
    .list-group-item {
      border: solid 2px green;
      border-radius: 5px;
    }
    @media (max-width: 1919px) {
      #SIMS {
        grid-template-columns: 1fr 1fr;
      }
    }
    @media (max-width: 787px) {
      #SIMS {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section("content")
  @if ($messages->count() > 0)
    <div style="margin-bottom:15px" id="warning" class="alert alert-danger no-margin">
      <h3 style="margin-top: 5px"> Sorry! </h3>
      @foreach($messages as $message)
        <p> {{ $message }} </p>
      @endforeach
    </div>
  @endif

  <div class="panel">
    <div class="panel-body">
      <p>
        Once you have registered for a session, it is difficult to make scheduling
        changes. Therefore, please be as certain as possible that the session you
        select is the one you want to attend. Sessions begin filling up in February,
        so register as early as possible and as accurately as possible.
      </p>

      <p>
        If you have additional questions, please refer to the orientation website.
      </p>

      <p>
        If a session is listed as "0 remaining," please register for another session
        AND email orientation@roanoke.edu to get on a waitlist if one of these
        sessions is your only option.  Session spots will fill on a first
        come-first-served basis.  If spots become available students will be
        contacted through their RC email.
      </p>
    </div>
  </div>


  <h2> Available Sessions </h2>

  <form action="{{ action("SIMSRegistrationController@store") }}" method="POST">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-xs-12">
        <div id="SIMS" class="list-group">
          @foreach($sessions as $session)
            @php
              $num_remaining = $session->registration_limit - $registrations[$session->id]->num_registrations;
            @endphp
            <div class="list-group-item">
              <dates>
                <start-date>{{ $session->start_date->format("F jS") }}</start-date>
                &ndash;
                <end-date>{{ $session->end_date->format("jS") }}</end-date>
              </dates>
              <attendance-cap> {{ $num_remaining }} / {{ $session->registration_limit }}</attendance-cap>
              <buttons>
                <label>
                  <input type="radio" name="orientation_session" value="{{ $session->id }}" @if($num_remaining <= 0) disabled @endif class="orientation_session" required/>
                  I want to attend this session
                </label>
              </buttons>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="form-group" style="text-align: right">
          <button type="submit" class="btn btn-primary">Reserve My Spot</button>
        </div>
      </div>
    </div>
  </form>
@endsection
