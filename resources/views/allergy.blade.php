@extends('forms_template')

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

	<style>
		textarea{
			margin-bottom:10px;
		}

		label{
			font-size:20px;
		}
	</style>
@endsection

@section("content")
	<form 	id = "AllergyInfo" method = "POST"
     	  	action = "{{ action("StudentInformationController@allergyInfoUpdate") }}">
		{{ csrf_field() }}

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="panel">
					<div class="panel-body">
						<strong> In accordance with the Roanoke College Student Health Services Notice of Privacy Practices, Roanoke College will only disclose pertinent health information to campus safety and other emergency response personnel in the event of an emergency. </strong>
					</div>
				</div>

   	     		<div>
     				<div class="pretty p-default">
	            		<input class="hideshowbox" type = "checkbox" name = "medications" value = "medications" id = "medications" @if(!empty($medications) && $medications->take_medications) checked @endif />
	            		<div class="state p-primary">
	            			<label>I currently take medications.</label>
	            		</div>
	            	</div>
	            </div>

	            <div class="row" class="hidden_box" id="medications_span" @if(empty($medications->take_medications)) style="display:none" @endif>   	    	
		   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		   	     		<p>Please list all medications you are taking.</p>
		   	     		<textarea name="medications_text" rows="5" cols="50">@if(!empty($medications->medications)){{$medications->medications}}@endif</textarea>
		   	     	</div>
		   	    </div>

	            <div>
     				<div class="pretty p-default">
	            		<input class="hideshowbox" type = "checkbox" name = "med_allergies" value = "med_allergies" id = "med_allergies" @if(!empty($med_allergy) && $med_allergy->have_medication_allergies)checked @endif />
	            		<div class="state p-primary">
	            			<label>I have medication allergies.</label>
	            		</div>
	            	</div>
	            </div>

	            <div class="row" class="hidden_box" id="med_allergies_span" @if(empty($med_allergy->have_medication_allergies)) style="display:none" @endif>   	    	
		   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		   	     		<p>Please list all medicines you are allergic too.</p>
		   	     		<textarea name="med_allergy_text" rows="5" cols="50">@if(!empty($med_allergy->medication_allergies)){{$med_allergy->medication_allergies}}@endif</textarea>
		   	     	</div>
		   	    </div>

	            <div>
     				<div class="pretty p-default">
	            		<input class="hideshowbox" type = "checkbox" name = "insect_allergies" value = "insect_allergies" id = "insect_allergies" @if(!empty($insect_allergy) && $insect_allergy->have_insect_allergies)checked @endif />
	            		<div class="state p-primary">
	            			<label>I have insect and/or food allergies.</label>
	            		</div>
	            	</div>
	            </div>

	            <div class="row" class="hidden_box" id="insect_allergies_span" @if(empty($insect_allergy->have_insect_allergies)) style="display:none" @endif>   	    	
		   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		   	     		<p>Please list all insect and/or food that you are allergic too.</p>
		   	     		<textarea name="insect_allergy_text" rows="5" cols="50">@if(!empty($insect_allergy->insect_allergies)){{$insect_allergy->insect_allergies}}@endif</textarea>
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