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
    .student {
      font-size: 14pt;
      font-weight: 300;
    }
  </style>
@endsection

@section("content")
  <h3 >Alter Student Reservation </h3>
  <form action="{{ action("SIMSRegistrationController@adminRegistrationPullRegistration") }}" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="student_name">Student Name or RCID</label>
      {!!
        MustangBuilder::typeaheadAjax("student_name", action("SIMSRegistrationController@adminRegistrationTypeahead"), '',
                                      array("input_data_name"=>"input_data", "display_data_name"=>"display_data"), array("class"=>"typeahead", "autocomplete" => "Off"),
                                      "student_rcid", true)
      !!}
      <input type="hidden" name="student_rcid" id="student_rcid" />
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary"><span class="fal fa-download"></span> Pull Record</button>
    </div>
  </form>
@endsection
