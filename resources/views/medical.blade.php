	@extends('forms_template')

	@section('javascript')
		<script>
		function hideShowLocal(show){
			var other_span = document.getElementById("other_span");
			if(show == true){
				other_span.style.display = '';
			}else{
				other_span.style.display = 'none';
			}
		}

		$('#other').on('click', function(){
			hideShowLocal(this.checked);
		});
		</script>
	@endsection

	@section("content")
		<form 	id = "MedicalInfo" method = "POST"
	     	  	action = "{{ action("StudentInformationController@medicalInfoUpdate") }}">
			{{ csrf_field() }}

			<div class = "row">
	   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	   	     		<h4> List any serious health concerns: Check all that apply. </h4>
		   	     		@foreach($health_concerns as $concerns) 
		   	     			<div>
		   	     				<div class="pretty p-default">  	     			
				            		<input type = "checkbox" name = "concerns[]" value = "{{$concerns->id}}" id = "{{$concerns->id}}" @if(!empty($concerns->student_concerns)) checked @endif />
				            		<div class="state p-primary">
					            		<label> {{$concerns->description}}</label>
					            	</div>
			   	     			</div>
		   	     			</div>
		   	     		@endforeach

	   	     		<div>
	     				<div class="pretty p-default">  	 
			        		<input type = "checkbox" name = "other" value = "other" id = "other"
			    			@if(!empty($other_concern)) checked @endif />
			    			<div class="state p-primary">
				    			<label>Other</label>
				    		</div>
			    		</div>
			    	</div>
	   	     	</div>
	   	    </div>

	   	    <div class="row" id="other_span" @if(empty($other_concern)) style="display:none" @endif>   	    	
	   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	   	     		<h4> Please list any other health concerns </h4>
	   	     		<textarea name="other_concerns" rows="5" cols="50">@if(!empty($other_concern)){{$other_concern->other_concern}}@endif</textarea>
	   	     	</div>
	   	    </div>

	   	    <div class = "row">
	        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	    		    <button type = "submit" class = "btn btn-lg btn-success pull-right"> Submit </button>
	        	</div>
	        </div>
	    </form>
	@endsection