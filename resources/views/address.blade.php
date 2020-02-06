@extends('forms_template')

@section('heading')
	Address Information
@endsection

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
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
	<style>
		@media(min-width: 767px) {
          .address{
          	//padding-top:17px;
          }
      }
	</style>
@endsection

@section("content")
	<form id="AddressForm" method="POST"
     	  action="{{ action("StudentInformationController@addressInfoUpdate") }}">
		{{ csrf_field() }}

		<h4> Home Address </h4>
		@include("partials.address", ['postfix' => "_home", "address" => $home_address, "required" => true])

 		<h4> Billing Address </h4>
		<div class="row">
   		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
   			<div class="pretty p-default">
      		<input type="checkbox" name="home_as_billing" value="1" id="home_as_billing" @if($student->home_as_billing) checked @endif />
      		<div class="state p-primary">
						<label for="home_as_billing">
							Use my home address
						</label>
					</div>
		    </div>
			</div>
		</div>

		<span id="billing" @if($student->home_as_billing) style="display:none" @endif>
			@include("partials.address", ['postfix' => "_billing", "address" => $billing_address, "required" => true])
   	</span>

    <div class="row">
    	<div class="col-xs-12">
    		<div class="btn-toolbar">
    	    <button type="submit" class="btn btn-lg btn-success pull-right">
						Save and Continue
					</button>
       		<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right">
						Cancel
					</a>
    		</div>
    	</div>
    </div>
  </form>
@endsection
