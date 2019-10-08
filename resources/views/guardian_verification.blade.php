@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Parent / Guardian Information
@endsection

@section("stylesheets")
	@parent
@endsection

@section("content")
	<form 	id = "guardian_verification" method = "POST"
     	  	action = "{{ action('StudentInformationController@parentAndGuardianInfoUpdate', ['id'=>$id]) }}">
		{{ csrf_field() }}

		<h3> Parent / Guardian Information </h3>
		<div class = "row">
   	     	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        First Name: <input type= "text" class = "form-control" name = "first_name" id = "first_name"  
			        				@if(!empty($guardian)) value="{{$guardian->first_name}}" @endif>
				</div>
        	</div>

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Nick Name: <input type= "text" class = "form-control" name = "nick_name" id = "nick_name"  
			        				@if(!empty($guardian)) value="{{$guardian->nick_name}}" @endif>
				</div>
        	</div>
   		</div>

		<div class = "row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Middle Name: <input type= "text" class = "form-control" name = "middle_name" id = "middle_name"  
			        				@if(!empty($guardian)) value="{{$guardian->middle_name}}" @endif>
				</div>
        	</div>  

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Last Name: <input type= "text" class = "form-control" name = "last_name" id = "last_name"  
			        				@if(!empty($guardian)) value="{{$guardian->last_name}}" @endif>
				</div>
        	</div>        	   	
   		</div>

   		<div class = "row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Relationship: <input type= "text" class = "form-control" name = "relationship" id = "relationship"  
			        				@if(!empty($guardian)) value="{{$guardian->relationship}}" @endif>
				</div>
        	</div>  

        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
	        		Marital Status
				    <select name = "MaritalStatus" form = "guardian_verification" class ="form-control" id = 'MaritalStatuses'>
				    	@foreach($marital as $marital_status)
	  						<option @if(!empty($guardian) && $guardian->fkey_marital_status == $marital_status->key_maritalStatus) selected @endif 
	  						value="{{$marital_status->key_maritalStatus}}"> {{ $marital_status->description }} </option>
	  					@endforeach
				    </select>
				</div>
        	</div>        	   	
   		</div>


   		<h3> Contact Information </h3>
		<div class = "row">
	    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
			        Email: <input type= "text" class = "form-control" name = "email" id = "email" @if(!empty($guardian))
			        			  value="{{ $guardian->email }}" @endif>
				</div>
	    	</div>
	    </div>
			
		<div class = "row">
	    	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Home Phone: <input type= "tel" class = "form-control" name = "home_phone" id = "home_phone"  
			        pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$"
			        			  	@if(!empty($guardian->home_phone)) value="{{$guardian->home_phone}}" @endif>
				</div>
	    	</div>

	    	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	        	<div class = "form-group">
			        Cell Phone: <input type= "tel" class = "form-control" name = "cell_phone" id = "cell_phone"  
			        pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$"
			        			  	@if(!empty($guardian->cell_phone)) value="{{$guardian->cell_phone}}" @endif>
				</div>
	    	</div>
		</div>

   		<h3> Home Address</h3>
   		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
			       	Street <input type= "text" class = "form-control" name = "Address1" id = "Address1"  
			        				@if(!empty($guardian)) value="{{$guardian->Address1}}" @endif>
				</div>
			</div>


   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        	
	        	<div class = "form-group address">
			        <input type= "text" class = "form-control "  name = "Address2" id = "Address2"  
			        	   @if(!empty($guardian)) value="{{$guardian->Address2}}" @endif>
				</div>
        	</div>
   		</div>

   		<div class="row">
	   		<div class="col-xs-12 col-md-6">
	        	<div class = "form-group">
	   				City <input type="text" class="form-control" name="city" id="city"
	   						@if(!empty($guardian)) value="{{$guardian->City}}" @endif>
	   			</div>
	   		</div>

	   		<div class="col-xs-12 col-md-6">
	        	<div class = "form-group address">
	   				State   <br>
	   				<select id="state" name="state" class="form-control">
	   					<option></option>
		   				@foreach($states as $state)
		   					<option value='{{$state->StateCode}}' @if(!empty($guardian) && ($guardian->fkey_StateId == $state->StateCode)) selected @endif>{{$state->StateName}}
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
	   						@if(!empty($guardian)) value="{{$guardian->PostalCode}}" @endif>
	   			</div>
	   		</div>

	   		<div class="col-xs-12 col-md-6">
	   			Country
			    <select name = "Country" class ="form-control" id = 'Country'>
					<option></option>
			    	@foreach($countries as $country)
						<option @if(!empty($guardian) && $guardian->fkey_CountryId == $country->key_CountryId) selected @endif 
						value="{{$country->key_CountryId}}"> {{ $country->CountryName }} </option>
					@endforeach
			    </select>
	   		</div>
   		</div>

    	<div class = "form-group">
    		<h3> Please address joint postal mailings to this household as follows:</h3>
    		<div class="col-xs-12 col-s m-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
			       	<input type="joint1" class="form-control" name="joint1" id="joint1"
						@if(!empty($guardian)) value="{{$guardian->joint_mail1}}" @endif>
				</div>
			</div>

   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        	
	        	<div class = "form-group address">
			        <input type="joint2" class="form-control" name="joint2" id="joint2"
						@if(!empty($guardian)) value="{{$guardian->joint_mail2}}" @endif>
				</div>
        	</div>	   					
		</div>



		<h3> Additional Information </h3>
		<div class="row">
			<div class="col-xs-12 col-md-6">
	        	<div class = "form-group address">
	   				Highest Education   <br>
	   				<select id="education" name="education" class="form-control">
	   					<option></option>
		   				@foreach($education as $education_level)
		   					<option value='{{$education_level->id}}' @if(!empty($guardian) && ($guardian->fkey_education_id == $education_level->id)) selected @endif>{{$education_level->education}}
		   					</option>
		   				@endforeach	
	   				</select>
	   			</div>
	   		</div>


			<div classs="col-xs-12 col-md-6">
				<p></p>
				<div>
					<div class="pretty p-default">
		        		<input type = "checkbox" name = "reside_with" value = "reside_with" id = "reside_with" @if(!empty($guardian) && $guardian->reside_with) checked @endif />
		        		<div class="state p-primary">
		        			<label>I reside with this parent/guardian</label>
		        		</div>
		        	</div>
	        	</div>
	        	<div>
					<div class="pretty p-default">
		        		<input type = "checkbox" name = "dependent" value="dependent" id = "independent_student" @if(!empty($guardian) && $guardian->claimed_dependent) checked @endif />
		        		<div class="state p-primary">
		        			<label>This Parent/Guardian Claims me as a tax dependent</label>
		        		</div>
		        	</div>
	        	</div>
	        </div>
        </div>

   	    <div class = "row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		    <button type = "submit" class = "btn btn-lg btn-success pull-right"> Submit </button>
        	</div>
        </div>
    </form>
@endsection