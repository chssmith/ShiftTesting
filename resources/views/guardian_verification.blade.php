@extends('forms_template')

@section('javascript')
	@parent

	<script>
		function show_hide_relationship_other () {
			if ($("#relationship").val() == 'O') {
				$("#relationship_other").fadeIn();
			} else {
				$("#relationship_other").fadeOut();
			}
		}

		$("#relationship").change(show_hide_relationship_other);

		show_hide_relationship_other();
	</script>

@endsection

@section('heading')
	Parent / Guardian Information
@endsection

@section("stylesheets")
	@parent
@endsection

@section("content")
	<form id="guardian_verification" method="POST"
				action="{{ action('StudentInformationController@parentAndGuardianInfoUpdate', ['id'=>$id]) }}">
		{{ csrf_field() }}

		<h3>Parent / Guardian Information</h3>
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="first_name">
						First Name <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
					</label>
					<input type="text" class="form-control" name="first_name" id="first_name" @if(!empty($guardian)) value="{{$guardian->first_name}}" @endif required>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="nick_name">
						Nick Name
					</label>
					<input type="text" class="form-control" name="nick_name" id="nick_name" @if(!empty($guardian)) value="{{$guardian->nick_name}}" @endif>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="middle_name">
						Middle Name
					</label>
					<input type="text" class="form-control" name="middle_name" id="middle_name" @if(!empty($guardian)) value="{{$guardian->middle_name}}" @endif>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="last_name">
						Last Name <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
					</label>
					<input type="text" class="form-control" name="last_name" id="last_name" @if(!empty($guardian)) value="{{$guardian->last_name}}" @endif required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="marital_status">
						Marital Status <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
					</label>
					<select name="marital_status" form="guardian_verification" class="form-control" id='marital_status' required>
						<option></option>
						@foreach($marital as $marital_status)
							<option @if(!empty($guardian) && $guardian->fkey_marital_status == $marital_status->code) selected @endif value="{{$marital_status->code}}">
								{{ $marital_status->description }}
							</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="relationship">
						Relationship <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
					</label>
					<select name="relationship" id="relationship" class="form-control">
						<option hidden>-- Please Select a Relationship Type --</option>
						@foreach($relationship_types as $relationship_type)
							<option value="{{$relationship_type->id}}" @if(!empty($guardian) && $guardian->relationship == $relationship_type->id)selected @endif>{{$relationship_type->type}}</option>
						@endforeach
					</select>
					<div id="relationship_other_container" style="margin-top: 15px; height: 20px;">
						<input type="text" class="form-control" name="relationship_other"
									 id="relationship_other" @if(!empty($guardian)) value="{{$guardian->relationship_other_description}}" @endif placeholder="Relationship Type" />
				 	</div>
				</div>
			</div>
		</div>


		<h3> Contact Information </h3>
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group">
					<label for="email">
						Email
					</label>
					<input type="text" class="form-control" name="email" id="email" @if(!empty($guardian)) value="{{ $guardian->email }}" @endif>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="home_phone">
						Home Phone
					</label>
					<input type= "tel" class="form-control" name="home_phone" id="home_phone" pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" @if(!empty($guardian->home_phone)) value="{{$guardian->home_phone}}" @endif>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="cell_phone">
						Cell Phone
					</label>
					<input type="tel" class="form-control" name="cell_phone" id="cell_phone" pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" @if(!empty($guardian->cell_phone)) value="{{$guardian->cell_phone}}" @endif>
				</div>
			</div>
		</div>

		<h3> Home Address</h3>
		@include("partials.address", ['postfix' => '', 'required' => true])

		<div class="form-group">
			<h3> Please address joint postal mailings to this household as follows:</h3>
			<div class="col-xs-12">
				<div class="form-group">
					<input type="text" class="form-control" name="joint1" id="joint1" @if(!empty($guardian)) value="{{$guardian->joint_mail1}}" @endif>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<input type="text" class="form-control" name="joint2" id="joint2" @if(!empty($guardian)) value="{{$guardian->joint_mail2}}" @endif>
				</div>
			</div>
		</div>



		<h3> Additional Information </h3>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group address">
					<label for="education">
						Highest Education <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
					</label>
					<select id="education" name="education" class="form-control">
						<option></option>
						@foreach($education as $education_level)
							<option value='{{$education_level->id}}' @if(!empty($guardian) && ($guardian->fkey_education_id == $education_level->id)) selected @endif>
								{{$education_level->education}}
							</option>
	   				@endforeach
   				</select>
   			</div>
   		</div>

			<div classs="col-xs-12 col-md-6">
				<div>
					<div class="pretty p-default">
						<input type="checkbox" name="reside_with" value="reside_with" id="reside_with" @if(!empty($guardian) && $guardian->reside_with) checked @endif />
						<div class="state p-primary">
							<label>I reside with this parent/guardian</label>
						</div>
					</div>
				</div>
				<div>
					<div class="pretty p-default">
						<input type="checkbox" name="dependent" value="dependent" id="independent_student" @if(!empty($guardian) && $guardian->claimed_dependent) checked @endif />
						<div class="state p-primary">
							<label>This parent/guardian claims me as a tax dependent</label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12" style="text-align: right">
				<a href="{{ url()->previous() }}" class="btn btn-lg btn-danger">Cancel</a>
				<button type = "submit" class = "btn btn-lg btn-success"> Save and Continue </button>
			</div>
		</div>
	</form>
@endsection
