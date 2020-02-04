@extends('forms_template')

@section('heading')
  SIMS Registration
@endsection

@section("header")
@endsection

@section('javascript')
  <script>

    function update(){
      //dietary needs
      if($("input[name='has_dietary_needs']:checked").val() == "yes"){
        $("#dietary-needs-describe").attr("hidden", false);
      }else{
        $("#dietary-needs-describe").attr("hidden", true);
      }
      //physical needs
      if($("input[name='has_physical_needs']:checked").val() == "yes"){
        $("#physical-needs-describe").attr("hidden", false);
      }else{
        $("#physical-needs-describe").attr("hidden", true);
      }
    }

    $(document).ready(function(){
      $("input[type='radio']").change(update);

      $("input[name='has_dietary_needs'][value='{{$sess["has_dietary_needs"]}}']").prop("checked", true);
      $("input[name='has_physical_needs'][value='{{$sess["has_physical_needs"]}}']").prop("checked", true);
      update();
    });
  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection

@section("content")
  @include("sims.partials.tabs")
  <h2> Student Information </h2>
  <form action="{{action("SIMSRegistrationController@studentInfo")}}" method="POST">
    {{csrf_field()}}
    <div class="form-group">
      <div class="row">
        <div class="col-md-6">
          <label name="preferred_name">Preferred First Name</label>
          <input name="preferred_name" class="form-control" type="text" value="{{$sess["nick_name"]}}" required />
        </div>
        <div class="col-md-6">
          <label name="phone">Cell Phone</label>
          <input name="phone" class="form-control" type="phone" value="{{$sess["cell_phone"]}}" required />
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <label name="gender">For purposes of overnight roommate assignments, please indicate gender</label>
          <select name="gender" class="form-control" value="{{$sess["gender"]}}" required >
            <option disabled hidden value="">-Please Select an Option-</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <label name="has_dietary_needs">Do you have any special dietary needs (vegitarian, allergies, vegan, ect.)?</label><br>
          <input type="radio" name="has_dietary_needs" value="yes" required> Yes&nbsp;
          <input type="radio" name="has_dietary_needs" value="no"> No
        </div>
        <div class="col-md-12" id="dietary-needs-describe" hidden>
          <label name="dietary_needs">Please Describe</label>
          <textarea name="dietary_needs" class="form-control" >{{$sess["dietary_needs"]}}</textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <label name="has_physical_needs">Do you have any physical needs which need accommodating?</label><br>
          <input type="radio" name="has_physical_needs" value="yes" required> Yes&nbsp;
          <input type="radio" name="has_physical_needs" value="no"> No
        </div>
        <div class="col-md-12" id="physical-needs-describe" hidden>
          <label name="physical_needs">Please Describe</label>
          <textarea name="physical_needs" class="form-control" >{{$sess["physical_needs"]}}</textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-md btn-info pull-right">Next <span class="fas fa-arrow-right"></span></button>
        </div>
      </div>
    </div>
  </form>
@endsection
