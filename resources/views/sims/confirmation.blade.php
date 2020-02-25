@extends('forms_template')

@section('heading')
  Summer Orientation Registration
@endsection

@section("header")
@endsection

@section('javascript')
  <script>

  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
  <style>
    .fa-times {
      color: #CB0D0B;
    }
    .fa-check {
      color: #70A204;
    }
  </style>
@endsection

@section("content")
  @include("sims.partials.tabs")
  <h2> Confirmation </h2>
  <form action="{{action("SIMSRegistrationController@confirmation")}}" method="POST">
    {{csrf_field()}}
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <div class="ljist-group">
            <a href="{{action("SIMSRegistrationController@studentInfoPage")}}" class="list-group-item">
              Student Info
              <span class="pull-right fas @if($student_info) fa-check @else fa-times @endif"></span>
            </a>
            <a href="{{action("SIMSRegistrationController@parentsGuestsPage")}}" class="list-group-item">
              {{$num_guests}} Guests
              <span class="pull-right fas @if($guests) fa-check @else fa-times @endif"></span>
            </a>
            <a href="{{action("SIMSRegistrationController@modeOfTravelPage")}}" class="list-group-item">
              Mode of Travel
              <span class="pull-right fas @if($mode_of_travel) fa-check @else fa-times @endif"></span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-success btn-md pull-right" @if($stop) disabled  @endif>Submit</button>
        </div>
      </div>
    </div>
  </form>
@endsection
