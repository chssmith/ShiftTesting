@extends('forms_template')

@section('heading')
	Personal Information
@endsection

@section('javascript')
	
@endsection



@section("content")
	<form 	id = "PersonalInfoForm" method = "POST"
     	  	action = "{{ action("StudentInformationController@personalInfoUpdate") }}">
		{{ csrf_field() }}

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        First Name: <input type= "text" class = "form-control" name = "first_name" id = "first_name"  
			        				@if(!empty($datamart_student)) value="{{$datamart_student->FirstName}}" 
			        				@elseif(!empty($student)) 	   value="{{$student->first_name}}" 
			        				@endif>
				</div>
        	</div>

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Middle Name: <input type= "text" class = "form-control" name = "middle_name" id = "middle_name"   
			        				@if(!empty($datamart_student)) value="{{$datamart_student->MiddleName}}" 
			        				@elseif(!empty($student)) 	   value="{{$student->middle_name}}"
			        				@endif>
				</div>
        	</div>

   		</div>
		<div class = "row">

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Last Name: <input type= "text" class = "form-control" name = "last_name" id = "last_name"   
			        				@if(!empty($datamart_student)) value="{{$datamart_student->LastName}}" 
			        				@elseif(!empty($student)) 	   value="{{$student->last_name}}" 
			        				@endif>
				</div>
        	</div>

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Maiden Name: <input type= "text" class = "form-control" name = "maiden_name" id = "maiden_name"   
			        				@if(!empty($datamart_student)) value="{{$datamart_student->MaidenName}}" 
			        				@elseif(!empty($student)) 	   value="{{$student->maiden_name}}" 
			        				@endif>
				</div>
        	</div>
   		</div>

		<div class = "row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        		SSN area (IDK where this info lives atm to check)
        	</div>        
   		</div>

		<div class = "row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
			        Email: <input type= "text" class = "form-control" name = "email" id = "email" disabled 
			        			  value="{{ $user->username }}@mail.roanoke.edu">
				</div>
        	</div>
        </div>
   		
		<div class = "row">
        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Home Phone: <input type= "text" class = "form-control" name = "home_phone" id = "home_phone"  
			        			  	@if(!empty($home_phone)) value="{{$home_phone->PhoneNumber}}" @endif>
				</div>
        	</div>

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Cell Phone: <input type= "text" class = "form-control" name = "cell_phone" id = "cell_phone"  
			        			  	@if(!empty($cell_phone)) value="{{$cell_phone->PhoneNumber}}" @endif>
				</div>
        	</div>
        </div>
   		
		<div class = "row">
        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		      	Marital Status
			    <select name = "MaritalStatus" form = "PersonalInfoForm" class ="form-control" id = 'MaritalStatuses'>
			    	@foreach($marital_statuses as $marital_status)
  						<option @if(!empty($student) && $student->fkey_marital_status == $marital_status->key_maritalStatus) selected @endif 
  						value="{{$marital_status->key_maritalStatus}}"> {{ $marital_status->description }} </option>
  					@endforeach
			    </select>
        	</div>

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		      	Military Status
			    <select name = "MilitaryStatus" form = "PersonalInfoForm" class ="form-control" id = 'MilitaryStatuses'>
			    	@foreach($military_options as $military_option)
  						<option @if(!empty($student) && ($student->fkey_military_id == $military_option->id)) selected @endif 
  						value="{{$military_option->id}}"> {{ $military_option->description }} </option>
  					@endforeach
			    </select>
        	</div>
	    </div>

	    <br>

	    <div class = "row">
        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        		Are you Hispanic or Latino? 
    			<div>
	     				<div class="pretty p-default p-round">
				    	<input type = "radio" class="form-group" name = "ethnics" value = "1" id="1" @if(!empty($student) && ($student->ethnics == 1)) checked @endif/> 
	            		<div class="state p-primary"><label>Yes</label></div>
				    </div>
				</div>

    			<div>
	     				<div class="pretty p-default p-round">
				    	<input type = "radio" class="form-group" name = "ethnics" value = "0" id="0" @if(!empty($student) && ($student->ethnics == 0)) checked @endif/> 
	            		<div class="state p-primary"><label>No</label> </div>
			    	</div>
		    	</div>
        	</div>
        </div>

         <div class = "row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    			<h5>Select one or more of the following races:</h5>
        		@foreach($all_races as $race)	 
        			<div>
   	     				<div class="pretty p-default">
		            		<input type = "checkbox" name = "races[]" value = "{{$race->code}}" id = "{{$race->code}}" @if(in_array($race->code,$user_races))checked @endif />
		            		<div class="state p-primary">
		            			<label>{{ $race->descr }}</label>
		            		</div>
		            	</div>
		            </div>
        		@endforeach
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