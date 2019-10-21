@extends('forms_template')

@section('heading')
	Citizenship Information
@endsection

@section('javascript')
	<script>
		function hideShow(show, target_id){
			var div_to_hideshow = document.getElementById(target_id + '_span');
			$(div_to_hideshow).toggle();
		}

		$('.hideshowbox').on('click', function(){
			hideShow(this.checked, this.id);
		});

		$('#states').on('change', function(){
			if(this.value == "VA"){
				$('#VA_span').show();
			}else{
				$('#VA_span').hide();
			}
		});

		$('.foreignCard').on('change', function(){
			$("#Visa_span").toggle();
		});
	</script>
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

@endsection

@section("content")
	<form id="CitizenForm" method="POST"
				action="{{ action("StudentInformationController@citizenInfoUpdate") }}">
		{{ csrf_field() }}

		<h3> Check all that apply </h3>

		<div>
			<div class="pretty p-default">
    		<input type="checkbox" class="hideshowbox" name="US_citizen" value=true id="US_citizen" @if($student->us_citizen) checked @endif />
    		<div class="state p-primary">
    			<label>I am a United States Citizen</label>
    		</div>
    	</div>
    </div>

    <div id="US_citizen_span" @if(!$student->us_citizen) hidden @endif>
    	<div class="row">
      	<div class="col-xs-12 col-sm-6 form-group">
					<label for="states">
	      		State of Residence
					</label>
		    	<select name="state" form="CitizenForm" class="form-control" id='states'>
 						<option></option>
			    	@foreach($states as $state)
  						<option value="{{$state->StateCode}}" @if(!empty($us_resident) && $us_resident->fkey_StateCode == $state->StateCode) selected @endif>
								{{ $state->StateName }}
							</option>
  					@endforeach
		    	</select>
        </div>
			</div>
	    <div id="VA_span" @if(empty($us_resident) || $us_resident->fkey_StateCode != "VA") hidden @endif>
	      <div class="row">
	      	<div class="col-xs-12 col-md-6 form-group">
						<label for="counties">
	        		City/County of Residence
						</label>
				    <select name="county" form="CitizenForm" class="form-control" id='counties'>
							<option></option>
				    	@foreach($counties as $id => $county)
								<option value="{{ $id }}" @if(!empty($us_resident) && $us_resident->fkey_CityCode == $id) selected @endif>
									{{ $county->display }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>

    <div>
			<div class="pretty p-default">
    		<input type = "checkbox" class="hideshowbox" name = "other_citizen" value = "other_citizen" id = "other_citizen" @if($student->other_citizen) checked @endif />
    		<div class="state p-primary">
    			<label>I am a citizen of another country.</label>
    		</div>
    	</div>
    </div>

    <div class="row" id="other_citizen_span" @if(count($foreign) == 0) hidden @endif>
     	@php($foreign_count = 1)
    	@foreach($foreign as $individual_country)
	     	@include("partials.citizenship_info")
		    @php($foreign_count++)
	    @endforeach

	    @php($individual_country = NULL)

	    @if($foreign_count < 3)
	    	@for($foreign_count = $foreign_count; $foreign_count < 3; $foreign_count++)
		     	@include("partials.citizenship_info")
		    @endfor
			@endif

			<div class="col-md-12">
        <div>
					<h3> I am in the U.S on a </h3>
				</div>
        <div>
					<div class="pretty p-default p-round">
		    		<input type = "radio" name = "GreenCard" class="foreignCard" value = "GreenCard" id = "GreenCard" @if($student->green_card) checked @endif />
		    		<div class="state p-primary p-round">
		    			<label>Green Card</label>
		    		</div>
		    	</div>

		    	<div class="pretty p-default p-round">
		    		<input type = "radio" class="foreignCard" name = "GreenCard" value = "Visa" id = "Visa" @if(!empty($visa)) checked @endif />
		    		<div class="state p-primary p-round">
		    			<label>Visa</label>
		    		</div>
		    	</div>

		    	<div class="row" id="Visa_span" @if(empty($visa)) hidden @endif>
	        	<div style="padding-left:0" class="col-xs-12 col-md-6">
							<label for="visa_type">
			    			Visa Type
							</label>
					    <select name="VisaTypes" form="CitizenForm" class="form-control" id='visa_type'>
				    		@foreach($visa_types as $visa_type)
	  							<option value="{{ $visa_type->code }}" @if(!empty($visa) && $visa->fkey_code == $visa_type->code) selected @endif>
										{{ $visa_type->descr }}
									</option>
		  					@endforeach
					    </select>
						</div>
					</div>
		    </div>
			</div>
    </div>

    <div class = "row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="btn-toolbar">
  		    <button type="submit" class="btn btn-lg btn-success pull-right"> Save and Continue </button>
      		<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
    		</div>
    	</div>
    </div>
	</form>
@endsection
