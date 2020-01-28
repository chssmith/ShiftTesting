@extends('forms_template')

@section('heading')
  SIMS Registration
@endsection

@section("header")
@endsection

@section('javascript')
  <script>
    function update(box_selector, describe_selector){
      //return a function so parameters can be passed on call
      return function(){
        if($(box_selector+":checked").val() == "yes"){
          $(describe_selector).attr("hidden", false);
        }else{
          $(describe_selector).attr("hidden", true);
        }
      }
    }

    $(document).ready(function(){
      selectors = [
                    ["input[name='g0_has_dietary_needs']", "#g0_dietary_needs"],
                    ["input[name='g1_has_dietary_needs']", "#g1_dietary_needs"],
                    ["input[name='g2_has_dietary_needs']", "#g2_dietary_needs"],
                    ["input[name='g3_has_dietary_needs']", "#g3_dietary_needs"],
                    ["input[name='g4_has_dietary_needs']", "#g4_dietary_needs"],
                    ["input[name='g0_has_physical_needs']", "#g0_physical_needs"],
                    ["input[name='g1_has_physical_needs']", "#g1_physical_needs"],
                    ["input[name='g2_has_physical_needs']", "#g2_physical_needs"],
                    ["input[name='g3_has_physical_needs']", "#g3_physical_needs"],
                    ["input[name='g4_has_physical_needs']", "#g4_physical_needs"]
                  ];
      for(i = 0; i < selectors.length; i++){
        $(selectors[i][0]).change(update(selectors[i][0], selectors[i][1]));
        update(selectors[i][0], selectors[i][1])
      }
    });

    let guests = [false, false, false, false, false];
    let num_guests = 0;
    $(document).on("click", "#add-guest", function(){
      if(num_guests < guests.length){
        num_guests += 1;
        let using = 0;
        while(using < guests.length-1 && guests[using]){
          using += 1;
        }
        $("#guests").append(String($("#guest_temp").html()).replace(/n\*/g, "g"+using));
        guests[using] = true;
        if(num_guests == guests.length){
          $(this).attr("disabled", true);
        }
      }
    });


    $(document).on("click", ".remove", function(){
      $("#"+$(this).attr("data")).remove();
      num_guests -= 1;
      guests[parseInt($(this).attr("data").substring(1))] = false;
      $("#add-guest").attr("disabled", false);
    });


  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection


@section("content")
  <h2> Parents, Guardians, Family, and Guests Attendance </h2>
  <p>We encourage you to attend as there is programming especially for parents, guardians, family and adult guests.  Lunch will be provided on both days of orientation.  In addition, if guests would like the college experience by staying on campus at no additional cost, have your New Maroon indicate this on their registration.  Lodging is limited to two guests per new student. Guests must be over the age of 16, unless accompanied by an adult.  Bring a sleeping bag, pillow and towel, as they will not be provided.  Guests will be housed in an on-campus residence hall separate from students.  Depending on the number of lodging requests, rooms may have a shared bathroom with another new student’s family.</p>
  <form action="{{action("SIMSRegistrationController@parentsGuests")}}" method="POST">
    {{csrf_field()}}
    <h3> Guests </h3>
    @include("sims.partials.guest")
    <div id="guests">
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="button" class="btn btn-md btn-info" id="add-guest"><span class="fas fa-user-plus"></span> Add Guest</button>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-md btn-info pull-right"><span class="fas fa-arrow-right"></span> Next</button>
        </div>
      </div>
    </div>

  </form>

  </div>
@endsection
