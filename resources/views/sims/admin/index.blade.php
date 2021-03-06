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
  <style>
    .list-group-item {
      font-size: 16pt;
    }
  </style>
@endsection

@section("content")
  @include("partials.warning")

  <h3>Admin Functions</h3>

  <div class="list-group">
    <a href="{{ action("SIMSRegistrationController@adminRegistrationLookup") }}" class="list-group-item">Alter Student Reservation</a>
    <a href="{{ action("SIMSRegistrationController@adminReservationReport") }}" class="list-group-item">Student Reservation Report</a>
    <a href="{{ action("SIMSRegistrationController@adminRegistrationPage") }}" class="list-group-item">Alter Student Registration</a>
    <a href="{{ action("SIMSRegistrationController@adminRegistrationReport") }}" class="list-group-item">Student Registration Report</a>
  </div>
@endsection
