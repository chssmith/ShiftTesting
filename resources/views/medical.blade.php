@extends('forms_template')

@section('heading')
	Medical Information
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
	<style>
		textarea{
			margin-bottom:10px;
			width: 50%;
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
	<form id="MedicalInfo" method="POST"
		action="{{ action("StudentForms\MedicalInformationController@store") }}">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-xs-12">
				<h4> List any serious health concerns: Check all that apply. </h4>
				@foreach($health_concerns as $concerns)
					<div>
						<div class="pretty p-default">
							<input type="checkbox" name="concerns[]" value="{{$concerns->id}}" id="{{$concerns->id}}" @if(!empty($concerns->student_concerns)) checked @endif />
							<div class="state p-primary">
								<label> {{$concerns->description}}</label>
							</div>
						</div>
					</div>
				@endforeach

				@php
						$has_other_concerns = (!empty($other_concern) || (empty($other_concern) && !empty($ods_other_concerns)));
				@endphp

				<div>
					<div class="pretty p-default">
						<input type="checkbox" name="other" value="other" id="other"
		    			@if(!empty($has_other_concerns)) checked @endif />
	    			<div class="state p-primary">
		    			<label>Other</label>
		    		</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" id="other_span" @if(!$has_other_concerns) style="display:none" @endif>
			<div class="col-xs-12">
				<label for="other_concerns"> Please list any other health concerns </label>
				<textarea name="other_concerns" id="other_concerns">@if(!empty($other_concern)){{$other_concern->other_concern}}@elseif($has_other_concerns){{$ods_other_concerns}}@endif</textarea>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-lg btn-success pull-right"> Save and Continue </button>
					<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
				</div>
			</div>
		</div>
	</form>
@endsection
