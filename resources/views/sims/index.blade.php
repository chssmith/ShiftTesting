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
	<link type="text/css" rel="stylesheet" href="{{ asset("css/sims_registration.css") }}" />
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
        select is the one you want to attend. Sessions begin filling up in March,
        so register as early as possible and as accurately as possible.
      </p>

      <p>
        If you have additional questions, please refer to the <a href="https://www.roanoke.edu/orientation">https://www.roanoke.edu/orientation</a>.
      </p>

      <p>
        If a session is listed as "0 remaining," please register for another
        session AND email <a href="mailto:orientation@roanoke.edu">orientation@roanoke.edu</a>
        to get on a waitlist.  Session
        spots will fill on a first-come-first-served basis.  If spots become
        available students will be contacted through their RC email.
      </p>
    </div>
  </div>


  <h2> Available Sessions </h2>

  <form action="{{ action("SIMSRegistrationController@store") }}" method="POST">
    {{ csrf_field() }}

    @include("sims.admin.partials.sessions_panel")

    <div class="row">
      <div class="col-xs-12">
        <div class="form-group" style="text-align: right">
          <button type="submit" class="btn btn-primary">Reserve My Spot</button>
        </div>
      </div>
    </div>
  </form>
@endsection
