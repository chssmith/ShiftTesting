@extends('forms_template')

@section('heading')
	Personal Information
@endsection

@section('javascript')

@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
	<style>
		#SSN {
			font-size: 12pt;
		}
	</style>
@endsection

@section("content")
	<form id="PersonalInfoForm" method="POST"
     	  	action="{{ action("StudentInformationController@personalInfoUpdate") }}">
		{{ @csrf_field() }}

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="first_name">
						First Name
					</label>
					<input type= "text" class="form-control" name="first_name" id="first_name"
							@if (!empty($datamart_student)) value="{{$datamart_student->FirstName}}"
	    				@elseif (!empty($student)) 	    value="{{$student->first_name}}"
	    				@endif />
				</div>
			</div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	     	<div class="form-group">
					<label for="middle_name">
			     	Middle Name
					</label>
					<input type= "text" class="form-control" name="middle_name" id="middle_name"
							@if (!empty($datamart_student)) value="{{$datamart_student->MiddleName}}"
							@elseif (!empty($student)) 	   value="{{$student->middle_name}}"
							@endif />
				</div>
    	</div>
		</div>

		<div class="row">
    	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      	<div class="form-group">
					<label for="last_name">
						Last Name
					</label>
					<input type= "text" class="form-control" name="last_name" id="last_name"
			    		@if (!empty($datamart_student)) value="{{$datamart_student->LastName}}"
			    		@elseif (!empty($student)) 	   value="{{$student->last_name}}"
			    		@endif>
				</div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	     	<div class="form-group">
					<label for="maiden_name">
						Maiden Name
					</label>
					<input type= "text" class="form-control" name="maiden_name" id="maiden_name"
			    		@if (!empty($datamart_student)) value="{{$datamart_student->MaidenName}}"
			    		@elseif (!empty($student)) 	   value="{{$student->maiden_name}}"
			    		@endif>
				</div>
      </div>
   	</div>

		<div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<label for="SSN">Social Security Number</label>
				<p id="SSN">
					@if($have_SSN)

					@else
						We do not have your Social Security Number on file.  Please call (540) 375 - XXXX to securely provide your Social Security Number to us.
					@endif
				</p>
			</div>

    	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
	    	<div class="form-group">
					<label for="email">
						Email
					</label>
					<input type="text" class="form-control" name="email" id="email" disabled value="{{$vpb_user->CampusEmail}}" />
				</div>
      </div>
    </div>

		<div class = "row">
    	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	     	<div class = "form-group">
					<label for="home_phone">
						Home Phone
					</label>
					<input type="text" class="form-control" name="home_phone" id="home_phone"
			  			@if (!empty($home_phone)) value="{{$home_phone->PhoneNumber}}" @endif>
				</div>
    	</div>

  		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	    	<div class = "form-group">
				  <label for="cell_phone">
						Cell Phone
					</label>
					<input type="text" class="form-control" name="cell_phone" id="cell_phone"
						@if (!empty($cell_phone)) value="{{$cell_phone->PhoneNumber}}" @endif>
				</div>
    	</div>
    </div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="MaritalStatuses">
						Marital Status
					</label>
			    <select name="MaritalStatus" form="PersonalInfoForm" class="form-control" id='MaritalStatuses'>
						@foreach ($marital_statuses as $marital_status)
							<option value="{{$marital_status->key_maritalStatus}}"
								@if (!empty($student) && $student->fkey_marital_status == $marital_status->key_maritalStatus) selected @endif>
								{{ $marital_status->description }}
							</option>
	  				@endforeach
			    </select>
				</div>
    	</div>

			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="MilitaryStatuses">
						Military Status
					</label>
					<select name="MilitaryStatus" form="PersonalInfoForm" class="form-control" id='MilitaryStatuses'>
						@foreach ($military_options as $military_option)
							<option value="{{$military_option->id}}"
								@if (!empty($student) && ($student->fkey_military_id == $military_option->id)) selected @endif>
								{{ $military_option->description }}
							</option>
						@endforeach
			    </select>
				</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
	    		<label>
						Are you Hispanic or Latino?
					</label>
					<div>
	   				<div class="pretty p-default p-round">
				    	<input type="radio" class="form-group" name="ethnics" value="1" id="1" @if (!empty($student) && ($student->ethnics == 1)) checked @endif/>
          		<div class="state p-primary"><label>Yes</label></div>
				    </div>
					</div>
	  			<div>
	   				<div class="pretty p-default p-round">
				    	<input type="radio" class="form-group" name="ethnics" value="0" id="0" @if (!empty($student) && ($student->ethnics == 0)) checked @endif/>
          		<div class="state p-primary"><label>No</label></div>
			    	</div>
		    	</div>
				</div>
    	</div>

			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Select one or more of the following races</label>
	    		@foreach($all_races as $race)
	  				<div>
							<div class="pretty p-default">
								<input type="checkbox" name="races[]" value="{{$race->code}}" id="{{$race->code}}" @if (in_array($race->code, $user_races)) checked @endif />
	          		<div class="state p-primary">
	          			<label>{{ $race->descr }}</label>
	          		</div>
	          	</div>
	          </div>
	    		@endforeach
				</div>
    	</div>
    </div>

    <div class = "row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="btn-toolbar">
	 		    <button type = "submit" class = "btn btn-lg btn-success pull-right"> Save and Continue </button>
     			<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
				</div>
			</div>
		</div>
  </form>
@endsection
