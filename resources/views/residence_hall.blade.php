@extends('forms_template')

@section('heading')
	Residence Information
@endsection

@section('javascript')
	<script>
		function hideShowLocal(show){
			var local_span = document.getElementById("local");
			if(show == true){
				local_span.style.display = '';
			}else{
				local_span.style.display = 'none';
			}
		}

		$('#residence_hall').on('click', function(){
			hideShowLocal(!this.checked);
		});

		function hideShowLocalAddress(value){
			var local_address = document.getElementById("local");
			if(value == 'other'){
				local_address.style.display = '';
			}else{
				local_address.style.display = 'none';
			}
		}

		$('.residence_radios').on('click', function(){
			hideShowLocalAddress(this.value);
		});
	</script>
@endsection

@section('stylesheets')
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

	<style>
	 #living{
	 	padding-left:15px;
	 }
	</style>
@endsection

@section("content")
	<form 	id = "AddressForm" method = "POST"
     	  	action = "{{ action("StudentInformationController@residenceInfoUpdate") }}">
		{{ csrf_field() }}


   		<div class="row">
	   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		   		<h4 style="display: inline-block"> Where do you plan to live? <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span> </h4>
			    <div class = "form-group" id="living">
			    	<div>
	     				<div class="pretty p-default p-round">
	            			<input type = "radio" class="residence_radios" name = "residence" value = "hall"
	        			    @if(!$student->home_as_local && empty($local_address)) checked @endif />
		            		<div class="state p-primary">
		        			    <label for = "residence_hall">
		        			    	I plan to live in a residence hall
		        			    </label>
			            	</div>
	   	     			</div>
	     			</div>

	     			<div>
	     				<div class="pretty p-default p-round">
		            		<input type = "radio" class="residence_radios" name = "residence" value = "home"
            					@if($student->home_as_local) checked @endif />
		            		<div class="state p-primary">
		            			<label for = "residence_hall">
		            			   	I plan to live at home
		            			</label>
			            	</div>
	   	     			</div>
	     			</div>

	     			<div>
	     				<div class="pretty p-default p-round">
		            		<input type = "radio" class="residence_radios" name = "residence" value = "other"
				    			@if(!empty($local_address)) checked @endif />
		            		<div class="state p-primary">
			    				<label for = "residence_hall">
			    				 	I plan to live locally
								</label>
			            	</div>
	   	     			</div>
	     			</div>
			    </div>
			</div>
		</div>

		<span id="local" @if(empty($local_address)) style="display:none" @endif>
			<div class="row">
				<div class="col-sm-12 col-md-6 col-md-offset-3">
					<div class="rc-callout alert alert-warning light">
						<span class="fas fa-exclamation-triangle"></span>
						<div>
							<p>
								In order to live locally and not at home, you must have
								permission from the Residence Life office at Roanoke College.
								If you are not sure if you are permitted to stay off campus,
								contact the Residence Life office at <phone-number>(540) 375 - 2308</phone-number>.
							</p>
							<p>
								Completion of this form does not grant permission to live off campus.
							</p>
						</div>
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="align-self: center"></button>
					</div>
				</div>
			</div>
			<h4> Local Address </h4>
				<div class = "row">
		   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			        	<div class = "form-group">
					       	<label for="local_Address1">Street <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span></label>
									<input type= "text" class = "form-control" name = "local_Address1" id = "local_Address1"
					        				@if(!empty($local_address)) value="{{$local_address->Address1}}" @endif>
						</div>
					</div>


		   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			        	<div class = "form-group address">
					        <input type= "text" class = "form-control "  name = "local_Address2" id = "local_Address2"
					        	   @if(!empty($local_address)) value="{{$local_address->Address2}}" @endif>
						</div>
		        	</div>
		   		</div>

		   		<div class="row">
			   		<div class="col-xs-12 col-md-4">
			        	<div class = "form-group">
			   					<label for="local_city">City <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span></label> <input type="text" class="form-control" name="local_city" id="local_city"
			   						@if(!empty($local_address)) value="{{$local_address->City}}" @endif>
			   			</div>
			   		</div>
			   		<div class="col-xs-12 col-md-4">
			        	<div class = "form-group address">
			   					<label for="local_state">State <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span></label>
			   				<select id="local_state" name="local_state" class="form-control">
			   					<option hidden></option>
				   				@foreach($states as $state)
				   					<option value='{{$state->StateCode}}' @if(!empty($local_address) && ($local_address->fkey_StateId == $state->StateCode)) selected @endif>{{$state->StateName}}
				   					</option>
				   				@endforeach
			   				</select>
			   			</div>
			   		</div>
			   		<div class="col-xs-12 col-md-4">
			        	<div class = "form-group">
			   				<label for="local_zip">Zip Code <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span></label>
								<input type="text" class="form-control" name="local_zip" id="local_zip"
			   						@if(!empty($local_address)) value="{{$local_address->PostalCode}}" @endif>
			   			</div>
			   		</div>
		   		</div>
		   	</span>
		</span>

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
