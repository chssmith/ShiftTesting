@extends('forms_template')

@section('heading')
	Emergency Contacts
@endsection

@section('javascript')
	<script>
		$(document).on("click", ".delete_button", function () {
			$("#delete_confirmation_name").html($(this).parents("tr").find("td:first-child").html());
			$("#delete_confirmation_form").attr("action", $(this).data("href"));
		});
	</script>
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

	<style>
		table {
			margin-bottom:25px !important;
		}
		.table > tbody > tr > td {
			vertical-align: middle;
		}
		.table > tbody > tr > td:last-child {
			text-align: right;
		}
		.modal-body {
			font-size: 18pt;
			line-height: 1.4;
		}
	</style>
@endsection

@section("content")
	<h1>Current Emergency Contacts</h1>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-condensed table-striped no-margin">
				<thead>
					<tr>
						<th>Name</th>
						<th>Relationship</th>
						<th class="hidden-sm hidden-xs">Daytime Phone</th>
						<th class="hidden-sm hidden-xs">Evening Phone</th>
						<th class="hidden-sm hidden-xs">Cell Phone</th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					@if(!$contacts->isEmpty())
						@foreach($contacts as $contact)
							<tr>
								<td>{{$contact->name}}</td>
								<td>{{$contact->relationship}}</td>
								<td class="hidden-sm hidden-xs">{{$contact->day_phone}}</td>
								<td class="hidden-sm hidden-xs">{{$contact->evening_phone}}</td>
								<td class="hidden-sm hidden-xs">{{$contact->cell_phone}}</td>
								<td>
									<a href="{{action('StudentForms\EmergencyContactController@showEmergencyContact', ['id'=>$contact->id])}}" class="btn btn-primary btn-lg"><span class="far fa-edit fa-fw" aria-hidden="true"></span> <span class="hidden-sm hidden-xs">Edit</span></a>
									<button type="button" data-toggle="modal" data-target="#delete_confirmation" data-href="{{action('StudentForms\EmergencyContactController@deleteContact', ['id'=>$contact->id])}}" class="btn btn-danger btn-lg delete_button"> <span class="far fa-trash-alt fa-fw" aria-hidden="true"></span> <span class="hidden-sm hidden-xs">Delete</span></a>
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td style="text-align:center" colspan="7"> <strong>There is no data to display! </strong></td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="modal fade" id="delete_confirmation" tabindex="-1" role="dialog" aria-labelledby="delete_confirmation_title">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="delete_confirmation_title">Confirm Deletion</h4>
	      </div>
	      <div class="modal-body">
					<p>
						Are you sure you wish to remove <span id="delete_confirmation_name" style="font-weight: bold; font-style: italic;"></span> from your emergency contacts?
					</p>
	      </div>
	      <div class="modal-footer">
					<form method="POST" id="delete_confirmation_form">
						{!! csrf_field() !!}
						{!! method_field("DELETE") !!}
	        	<button type="submit" class="btn btn-danger">Delete</button>
					</form>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="row">
		<div class="col-md-6 col-md-offset-6 col-sm-12">
			<div class="btn-toolbar three">
				<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger"> <span class="far fa-times" aria-hidden="true"></span> Cancel </a>
				<a href="{{action('StudentForms\EmergencyContactController@showEmergencyContact')}}" class="btn btn-lg btn-info"> <span class="far fa-plus" aria-hidden="true"></span> New Contact </a>
				<a href="{{action('StudentForms\EmergencyContactController@emergencyDoubleCheck')}}" class="btn btn-lg btn-success"> <span class="fas fa-save" aria-hidden="true"></span> Save and Continue </a>
			</div>
		</div>
	</div>
@endsection
