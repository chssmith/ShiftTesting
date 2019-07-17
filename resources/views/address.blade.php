@extends('forms_template')

@section('javascript')
	<script>
		function hideShowBilling(show){
			var billing_span = document.getElementById("billing");
			if(show == true){
				billing_span.style.display = '';
			}else{
				billing_span.style.display = 'none';
			}
		}

		$('#home_as_billing').on('click', function(){
			hideShowBilling(!this.checked);
		});
	</script>
@endsection

@section('stylesheets')
	@parent
	<style>
		@media(min-width: 767px) {
          .address{
          	//padding-top:17px;
          }
      }
	</style>
@endsection

@section("content")
	<form 	id = "AddressForm" method = "POST"
     	  	action = "{{ action("StudentInformationController@addressInfoUpdate") }}">
		{{ csrf_field() }}

		<h4> Home Address </h4>
		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
			       	Street <input type= "text" class = "form-control" name = "Address1" id = "Address1"  
			        				@if(!empty($home_address)) value="{{$home_address->Address1}}" @endif>
				</div>
			</div>


   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        	
	        	<div class = "form-group address">
			        <input type= "text" class = "form-control "  name = "Address2" id = "Address2"  
			        	   @if(!empty($home_address)) value="{{$home_address->Address2}}" @endif>
				</div>
        	</div>
   		</div>

   		<div class="row">
	   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
	   				City <input type="text" class="form-control" name="city" id="city"
	   						@if(!empty($home_address)) value="{{$home_address->City}}" @endif>
	   			</div>
	   		</div>
   		</div>

   		<div class="row">
	   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group address">
	   				State   <br>
	   				<select id="state" name="state" class="form-control">
		   				@foreach($states as $state)
		   					<option value='{{$state->StateCode}}' @if(!empty($home_address) && ($home_address->fkey_StateId == $state->StateCode)) selected @endif>{{$state->StateName}}
		   					</option>
		   				@endforeach	
	   				</select>
	   			</div>
	   		</div>
   		</div>

   		<div class="row">
	   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        	<div class = "form-group">
	   				Zip Code <input type="text" class="form-control" name="zip" id="zip"
	   						@if(!empty($home_address)) value="{{$home_address->PostalCode}}" @endif>
	   			</div>
	   		</div>
   		</div>

   		<h4> Billing Address </h4>
   		<div class="row">
	   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	   			<div class="pretty p-default">
            		<input type = "checkbox" name = "home_as_billing" value = "1" id = "home_as_billing" @if($student->home_as_billing) checked @endif />
            		<div class="state p-primary"><label for = "home_as_billing"> Use my home address </label></div>
			    </div>
			</div>
		</div>

		<span id="billing" @if($student->home_as_billing) style="display:none" @endif>
			<div class = "row">
	   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        	<div class = "form-group">
				       	Street <input type= "text" class = "form-control" name = "billing_Address1" id = "billing_Address1"  
				        				@if(!empty($billing_address)) value="{{$billing_address->Address1}}" @endif>
					</div>
				</div>


	   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        	
		        	<div class = "form-group address">
				        <input type= "text" class = "form-control "  name = "billing_Address2" id = "billing_Address2"  
				        	   @if(!empty($billing_address)) value="{{$billing_address->Address2}}" @endif>
					</div>
	        	</div>
	   		</div>

	   		<div class="row">
		   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        	<div class = "form-group">
		   				City <input type="text" class="form-control" name="billing_city" id="billing_city"
		   						@if(!empty($billing_address)) value="{{$billing_address->City}}" @endif>
		   			</div>
		   		</div>
	   		</div>

	   		<div class="row">
		   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        	<div class = "form-group address">
		   				State   <br>
		   				<select id="billing_state" name="billing_state" class="form-control">
			   				@foreach($states as $state)
			   					<option value='{{$state->StateCode}}' @if(!empty($billing_address) && ($billing_address->fkey_StateId == $state->StateCode)) selected @endif>{{$state->StateName}}
			   					</option>
			   				@endforeach	
		   				</select>
		   			</div>
		   		</div>
	   		</div>

	   		<div class="row">
		   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        	<div class = "form-group">
		   				Zip Code <input type="text" class="form-control" name="billing_zip" id="billing_zip"
		   						@if(!empty($billing_address)) value="{{$billing_address->PostalCode}}" @endif>
		   			</div>
		   		</div>
	   		</div>
	   	</span>

        <div class = "row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		    <button type = "submit" class = "btn btn-lg btn-success pull-right"> Submit </button>
        	</div>
        </div>
    </form>
@endsection	