@extends('forms_template')

@section('heading')
	Admin
@endsection

@section('javascript')
@endsection

@section('stylesheets')
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
