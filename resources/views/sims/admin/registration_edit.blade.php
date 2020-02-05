@extends('forms_template')

@section('heading')
  Summer Orientation Registration Admin
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
  <h2>{{$student->display_name}} - Reservation Details</h2>

  <form action="{{ action("SIMSRegistrationController@adminRegistrationStore") }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="student_rcid" value="{{ $student->RCID }}" />
    @include("sims.admin.partials.sessions_panel")
    <div class="row">
      <div class="col-xs-12">
        <label>
          <input type="radio" name="orientation_session" value="-1" @if (isset($registration) && $registration->fkey_sims_session_id == -1) checked @endif required> I cannot attend any of the orientation sessions.
        </label>
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
