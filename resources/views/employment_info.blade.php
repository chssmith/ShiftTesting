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
	<form id="guardian_verification" method="POST"
				action="{{ action("StudentInformationController@employmentInfoUpdate", ['id'=>$guardian->id]) }}">
		{{ csrf_field() }}

		<h3> Employment info for {{$guardian->display_name}} </h3>

		<h4> Business Contact </h4>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="employer_name">
						Employer Name
					</label>
					<input type="text" class="form-control" name="employer_name" id="employer_name" @if(!empty($employment)) value="{{$employment->employer_name}}" @endif>
				</div>
    	</div>
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="position">
						Position
					</label>
					<input type="text" class="form-control" name="position" id="position" @if(!empty($employment)) value="{{$employment->position}}" @endif>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="business_number">
						Business Phone Number
					</label>
					<input type="text" class="form-control" name="business_number" id="business_number" @if(!empty($employment)) value="{{$employment->employer_name}}" @endif>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="business_email">
						Business Email
					</label>
					<input type= "text" class = "form-control" name="business_email" id="business_email" @if(!empty($employment)) value="{{$employment->business_email}}" @endif>
				</div>
			</div>
		</div>

		<h4> Business Address </h4>

		@include("partials.address", ["postfix" => "_business"])

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
