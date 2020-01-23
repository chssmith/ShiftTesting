@extends('forms_template')

@section('heading')
  SIMS Registration
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
@endsection

@section("content")
  <div class="progress" style="height: 4px;">
    <div class="progress-bar" role="progressbar" style="width: 40%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
  <h2> Parents, Guardians, Family, and Guests Attendance </h2>
  <p>We encourage you to attend as there is programming especially for parents, guardians, family and adult guests.  Lunch will be provided on both days of orientation.  In addition, if guests would like the college experience by staying on campus at no additional cost, have your New Maroon indicate this on their registration.  Lodging is limited to two guests per new student. Guests must be over the age of 16, unless accompanied by an adult.  Bring a sleeping bag, pillow and towel, as they will not be provided.  Guests will be housed in an on-campus residence hall separate from students.  Depending on the number of lodging requests, rooms may have a shared bathroom with another new student’s family.</p>
  <form action="{{action("SIMSRegistrationController@studentInfo")}}" method="POST">
    {{csrf_field()}}
    <h3> Parents/Guardians </h3>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          {{-- Add Parents --}}
        </div>
      </div>
    </div>
    <h3> Guests </h3>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          {{-- Add Guests --}}
        </div>
      </div>
    </div>

  </form>

  </div>
@endsection
