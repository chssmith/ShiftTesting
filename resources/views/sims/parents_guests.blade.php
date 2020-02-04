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
        no_box = $("#"+box_selector);
        desc = $(describe_selector);
        no_box.prop("checked", !no_box.prop("checked"));
        desc.prop("hidden", !desc.prop("hidden"));
      }
    }

    function on_campus_update(selector){
      //return a function so parameter can be passed on call
      return function(){
        box = $(`#${selector}`);
        box.prop("checked", !box.prop("checked"));
      }
    }

    let i = 0;

    function add(){
      $("#guests").append(String($("#guest_temp").html()).replace(/n\*/g, "g"+i));
      //dietary needs
      let selector = [`g${i}_has_dietary_needs`, `#g${i}_dietary_needs`];
      $("."+selector[0]).change(update(selector[0], selector[1]));
      //physical needs
      selector = [`g${i}_has_physical_needs`, `#g${i}_physical_needs`];
      $(`.${selector[0]}`).change(update(selector[0], selector[1]));
      //on campus
      selector = `g${i}_on_campus`;
      $(`.${selector}`).change(on_campus_update(selector));
      i += 1;
    }

    $(document).on("click", "#add-guest", add);

    $(document).on("click", ".remove", function(){
      $("#"+$(this).attr("data")).remove();
    });

    $(document).ready(function(){
      @if(count($sess) == 9)
        @for($i = 0; $i < count($sess["relationship"]); $i++)
          add();
          $(`#g${i-1}_relationship`).val("{{$sess["relationship"][$i]}}");
          $(`#g${i-1}_first_name`).val("{{$sess["first_name"][$i]}}");
          $(`#g${i-1}_last_name`).val("{{$sess["last_name"][$i]}}");
          $(`#g${i-1}_email`).val("{{$sess["email"][$i]}}");
          @if($sess["has_dietary_needs"][$i] == "yes")
            $(`.g${i-1}_has_dietary_needs`).prop("checked", true);
            $(`#g${i-1}_dietary_needs`).find("textarea").val("{{$sess["dietary_needs"][$i]}}");
            (update(`g${i-1}_has_dietary_needs`, `#g${i-1}_dietary_needs`))();
          @endif
          @if($sess["has_physical_needs"][$i] == "yes")
            $(`.g${i-1}_has_physical_needs`).prop("checked", true);
            $(`#g${i-1}_physical_needs`).find("textarea").val("{{$sess["physical_needs"][$i]}}");
            (update(`g${i-1}_has_physical_needs`, `#g${i-1}_physical_needs`))();
          @endif
          @if($sess["on_campus"][$i] == "yes")
            $(`.g${i-1}_on_campus`).prop("checked", true);
            (on_campus_update(`g${i-1}_on_campus`))()
          @endif
        @endfor
      @endif
    });

  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection


@section("content")
  @include("sims.partials.tabs")
  <h2> Parents, Guardians, Family, and Guests Attendance </h2>
  <p>We encourage you to attend as there is programming especially for parents, guardians, family and adult guests.  Lunch will be provided on both days of orientation.  In addition, if guests would like the college experience by staying on campus at no additional cost, have your New Maroon indicate this on their registration.  Lodging is limited to two guests per new student. Guests must be over the age of 16, unless accompanied by an adult.  Request for more than two guest need to be made by emailing orientation@roanoke.edu.  Bring a sleeping bag, pillow and towel, as they will not be provided.  Guests will be housed in an on-campus residence hall separate from students.  Depending on the number of lodging requests, rooms may have a shared bathroom with another new student’s family.</p>
  <br>
  <p>If a parent/guardian prefers to lodge off campus, please visit Virginia’s Blue Ridge page for information about local lodging & restaurants.</p>
  <br>
  <p>We also understand that some cannot be away from other obligations at that time of year.</p>


  @include("sims.partials.guest")

  <form action="{{action("SIMSRegistrationController@parentsGuests")}}" method="POST" id="guest_form">
    {{csrf_field()}}
    <h3> Guests </h3>

    <div id="guests">
      {{-- This is where guests are added to the page --}}
    </div>

    {{-- Add Guest Button --}}
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="button" class="btn btn-md btn-success" id="add-guest"><span class="fas fa-user-plus"></span> Add Guest</button>
        </div>
      </div>
    </div>

    {{-- Next Button --}}
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-md btn-info pull-right">Next <span class="fas fa-arrow-right"></span></button>
        </div>
      </div>
    </div>

  </form>

  </div>
@endsection
