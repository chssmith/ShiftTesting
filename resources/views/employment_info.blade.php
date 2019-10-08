@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Parent / Guardian Employment
@endsection

@section("stylesheets")
	@parent
@endsection

@section("content")
	<form 	id = "guardian_verification" method = "POST"
     	  	action = "{{ action("StudentInformationController@employmentInfoUpdate", ['id'=>$guardian->id]) }}">
		{{ csrf_field() }}

		<h3> Employment info for {{$guardian->first_name . ' '. $guardian->last_name}} </h3>
				
		<h3> Business Contact </h3>

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Employer Name: <input type= "text" class = "form-control" name = "employer_name" id = "employer_name"  
			        				@if(!empty($employment)) value="{{$employment->employer_name}}" @endif>
				</div>
        	</div>


   	     	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Position: <input type= "text" class = "form-control" name = "position" id = "position"  
			        				@if(!empty($employment)) value="{{$employment->position}}" @endif>
				</div>
        	</div>
        </div>

        <div class = "row">
   	     	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Business Phone Number: <input type= "text" class = "form-control" name = "business_number" id = "business_number"  
			        				@if(!empty($employment)) value="{{$employment->employer_name}}" @endif>
				</div>
        	</div>


   	     	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Business Email: <input type= "text" class = "form-control" name = "business_email" id = "business_email"  
			        				@if(!empty($employment)) value="{{$employment->position}}" @endif>
				</div>
        	</div>
        </div>

		<h3> Business Address </h3>

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
			       	Street <input type= "text" class = "form-control" name = "Address1" id = "Address1"  
			        				@if(!empty($employment)) value="{{$employment->Street1}}" @endif>
				</div>
			</div>


   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        	
	        	<div class = "form-group address">
			        <input type= "text" class = "form-control "  name = "Address2" id = "Address2"  
			        	   @if(!empty($employment)) value="{{$employment->Street2}}" @endif>
				</div>
        	</div>
   		</div>

   		<div class="row">
	   		<div class="col-xs-12 col-md-6">
	        	<div class = "form-group">
	   				City <input type="text" class="form-control" name="city" id="city"
	   						@if(!empty($employment)) value="{{$employment->city}}" @endif>
	   			</div>
	   		</div>  

	   		<div class="col-xs-12 col-md-6">
	        	<div class = "form-group address">
	   				State   <br>
	   				<select id="state" name="state" class="form-control">
	   					<option></option>
		   				@foreach($states as $state)
		   					<option value='{{$state->StateCode}}' @if(!empty($employment) && ($employment->fkey_StateCode == $state->StateCode)) selected @endif>{{$state->StateName}}
		   					</option>
		   				@endforeach	
	   				</select>
	   			</div>
	   		</div> 
	   	</div>

	   	<div class="row">
	   		<div class="col-xs-12 col-md-6">
	        	<div class = "form-group">
	   				Zip Code <input type="text" class="form-control" name="zip" id="zip"
	   						@if(!empty($employment)) value="{{$employment->postal_code}}" @endif>
	   			</div>
	   		</div>

	   		<div class="col-xs-12 col-md-6">
		   		Country
			    <select name = "Country" class ="form-control" id = 'Country'>
					<option></option>
			    	@foreach($countries as $country)
						<option @if(!empty($employment) && $employment->fkey_CountryId == $country->key_CountryId) selected @endif 
						value="{{$country->key_CountryId}}"> {{ $country->CountryName }} </option>
					@endforeach
			    </select>
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