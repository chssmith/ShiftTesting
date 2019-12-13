@extends('forms_template')

@section('heading')
	Allergy Information
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
	</script>
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
	<style>
		textarea{
			margin-bottom:10px;
			width: inherit;
			height: 200px;
		}

		label{
			font-size:20px;
			width: 100%;
		}
		@media(max-width: 767px) {
			textarea {
				width: 100%;
			}
		}
	</style>
@endsection

@section("content")
	<form id="AllergyInfo" method="POST" action="{{ action("StudentInformationController@allergyInfoUpdate") }}">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-sm-12 col-md-6 col-md-offset-3">
				<div class="rc-callout alert alert-warning light">
					<span class="fas fa-exclamation-triangle"></span>
					<strong> In accordance with the Roanoke College Student Health Services Notice of Privacy Practices, Roanoke College will only disclose pertinent health information to campus safety and other emergency response personnel in the event of an emergency. </strong>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="align-self: center"><span class="far fa-times"></span></button>
				</div>
			</div>
		</div>

		<div class="row">
   		<div class="col-sm-12 col-md-6">
				<div class="pretty p-default">
					<input class="hideshowbox" type="checkbox" name="medications" value="medications" id="medications" @if(!empty($medications) && $medications->take_medications) checked @endif />
					<div class="state p-primary">
						<label>I currently take medications.</label>
					</div>
				</div>

        <div class="row" class="hidden_box" id="medications_span" @if(empty($medications->take_medications)) style="display:none" @endif>
					<div class="col-xs-12">
						<label>
							Please list all medications you are taking.
						</label>
						<textarea name="medications_text" id="medications_text">@if(!empty($medications->medications)){{$medications->medications}}@endif</textarea>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
      <div class="col-sm-12 col-md-6">
 				<div class="pretty p-default">
					<input class="hideshowbox" type="checkbox" name="med_allergies" value="med_allergies" id="med_allergies" @if(!empty($med_allergy) && $med_allergy->have_medication_allergies)checked @endif />
					<div class="state p-primary">
						<label>I have medication allergies.</label>
					</div>
				</div>

				<div class="row" class="hidden_box" id="med_allergies_span" @if(empty($med_allergy->have_medication_allergies)) style="display:none" @endif>
					<div class="col-xs-12 form-group">
						<label for="med_allergy_text">
							Please list all medicines you are allergic to.
						</label>
   	     		<textarea id="med_allergy_text" name="med_allergy_text">@if(!empty($med_allergy->medication_allergies)){{$med_allergy->medication_allergies}}@endif</textarea>
   	     	</div>
   	    </div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="pretty p-default">
					<input class="hideshowbox" type="checkbox" name="insect_allergies" value="insect_allergies" id="insect_allergies" @if(!empty($insect_allergy) && $insect_allergy->have_insect_allergies)checked @endif />
					<div class="state p-primary">
						<label>I have insect and/or food allergies.</label>
					</div>
				</div>

        <div class="row" class="hidden_box" id="insect_allergies_span" @if(empty($insect_allergy->have_insect_allergies)) style="display:none" @endif>
					<div class="col-xs-12 form-group">
						<label for="insect_allergy_text">Please list all insects and/or foods that you are allergic too.</label>
 	     			<textarea id="insect_allergy_text" name="insect_allergy_text">@if(!empty($insect_allergy->insect_allergies)){{$insect_allergy->insect_allergies}}@endif</textarea>
					</div>
				</div>
			</div>
		</div>

		<div class = "row">
			<div class="col-xs-12">
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-lg btn-success pull-right"> Save and Continue </button>
      		<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
    		</div>
    	</div>
    </div>
  </form>
@endsection
