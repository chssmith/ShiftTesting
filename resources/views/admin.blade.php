@extends('forms_template')

@section('heading')
	Admin
@endsection

@section('javascript')
@endsection

@section('stylesheets')
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection

@section("content")
	<div class="list-group">
		<div class="list-group-item">
  		<a href="{{action('AdminController@changedStudents')}}" class="btn btn-primary"> Changed Student Forms</a>
			<button class="btn btn-danger pull-right" data-toggle="modal" data-target="#process_students">Mark Students Processed</button>
		</div>
		<div class="list-group-item">
  		<a href="{{action('AdminController@changedParentInfo')}}" class="btn btn-primary"> Changed Parent Info </a>
			<button class="btn btn-danger pull-right" data-toggle="modal" data-target="#process_parents">Mark Parents Processed</button>
		</div>
		<a href="#" data-toggle="modal" data-target="#lookup_modal" class="list-group-item">
			Lookup Missing Data by Student
		</a>
  </div>

	<div class="modal fade" id="lookup_modal" tabindex="-1" role="dialog" aria-labelledby="lookup_modal_label">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="lookup_modal_label">Lookup Student</h4>
	      </div>
				<form action="{{ action("AdminController@lookupMissingInfo") }}" method="POST">
					{{ csrf_field() }}
		      <div class="modal-body">
						<div class="form-group">
				      <label for="student_name">Student Name or RCID</label>
				      {!!
				        MustangBuilder::typeaheadAjax("student_name", action("SIMSRegistrationController@adminRegistrationTypeahead"), '',
				                                      array("input_data_name"=>"input_data", "display_data_name"=>"display_data"), array("class"=>"typeahead"),
				                                      "student", true)
				      !!}
				      <input type="hidden" name="student" id="student" />
				    </div>
		      </div>
		      <div class="modal-footer">
		        	<button type="submit" class="btn btn-primary"><span class="far fa-search"></span> Get Data</button>
		      </div>
				</form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="process_students" tabindex="-1" role="dialog" aria-labelledby="process_students_label">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="process_students_label">Mark Students Processed</h4>
	      </div>
	      <div class="modal-body">
					<p>
						Are you sure you would like to mark all changed student data as processed?
						Any students who have completed the form will not appear on future exports of the changed student file.
					</p>
	      </div>
	      <div class="modal-footer">
					<form action="{{ action("AdminController@markStudentsProcessed") }}" method="POST">
						{{ csrf_field() }}
	        	<button type="submit" class="btn btn-primary">Submit</button>
					</form>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="process_parents" tabindex="-1" role="dialog" aria-labelledby="process_parents_label">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="process_parents_label">Mark Parents Processed</h4>
	      </div>
	      <div class="modal-body">
					<p>
						Are you sure you would like to mark all changed parent data as processed?
						Any students who have completed the form will not appear on future exports of the changed parent file.
					</p>
	      </div>
	      <div class="modal-footer">
					<form action="{{ action("AdminController@markParentsProcessed") }}" method="POST">
						{{ csrf_field() }}
	        	<button type="submit" class="btn btn-primary">Submit</button>
					</form>
	      </div>
	    </div>
	  </div>
	</div>
@endsection
