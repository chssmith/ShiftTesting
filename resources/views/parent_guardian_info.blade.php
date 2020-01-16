@extends('forms_template')

@section('javascript')
	<script>
		$(document).on("click", ".delete", function () {
			var action_url = $(this).data("href");
			var name       = $(this).parents("tr").find("td:first-child").html();

			$("#delete-form").attr("action", action_url);
			$("#delete_name").html(name);
		});

		$(document).on("click", ".confirm", function () {
			var request_url = $(this).data("href");
			$.ajax({
				url: request_url,
				method: "GET",
				success: function (response) {
					$("#confirm_modal .modal-body").html(response);
				}
			});
		});
	</script>
@endsection

@section('heading')
	Parent / Guardian Information
@endsection

@section("stylesheets")
	@parent
	<link rel="stylesheet" type="text/css" href="{{ asset("css/global.css") }}" />
	<style>

		label{
			font-size:20px;
		}

		.modal-body label {
			font-size: unset;
		}

		ul{
			list-style-type:disc;
			padding-left:25px;
		}

		.panel-body ul > li{
			padding: 10px 0px;
		}

		table {
			margin-bottom:25px !important;
		}

		.row-buttons {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr 1fr;
			grid-gap: 10px;
		}

		#delete_name {
			font-weight: bold;
			font-style: italic;
		}

		@media(max-width: 1000px) {
			.row-buttons {
				grid-template-columns: 1fr;
				grid-column-start: 1;
			}
		}

		@media(min-width: 1000px) {
			.row-buttons > *:first-child {
				grid-column-start: 2;
			}

			table td:last-child {
				text-align: right;
			}
		}

	</style>
@endsection

@section("content")
	<div class="panel">
		<div class="panel-body">
			For each parent or guardian, please make sure the information on each screen is correct. In order to complete this requirement, please make sure to do the following:

			<ul>
				<li>
					Verify the contact, student information, and business information for all parents and guardians currently listed is correct.
				</li>

				<li>
					Add any parents or guardians that Roanoke College does not currently have on file.
				</li>

				<li>
					Delete any parents or guardians that you would like removed from your record.
				</li>

				<li>
					Once all parents and guardians have been verified, click on the "Submit" button. You will be notified by email that you've completed this requirement.
				</li>
			</ul>
		</div>
	</div>

	<table class="table table-striped table-condensed no-margin">
		<thead>
			<tr>
				<th>Parent/Guardian Name</th>
				<th class="hidden-xs hidden-sm">Relationship</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($guardians[0]))
				@foreach($guardians as $guardian)
					<tr>
						<td>{{ $guardian->display_name }}</td>
						<td class="hidden-xs hidden-sm">{{$guardian->relationship}}</td>
						<td>
							<div class="row-buttons">
								<a href="{{action('StudentInformationController@individualGuardian', ['id'=>$guardian->id])}}" class="btn btn-primary"><span class="far fa-edit" aria-hidden="true"></span> Edit</a>
								<button type="button" data-href="{{ action("StudentInformationController@getGuardianVerification", ["id" => $guardian->id]) }}" class="btn btn-info confirm" data-toggle="modal" data-target="#confirm_modal"> <span class="fas fa-check" aria-hidden="true"></span> Verify</button>
								<button type="button" data-href="{{ action('StudentInformationController@deleteGuardian', ['id'=>$guardian->id]) }}" class="btn btn-danger delete" data-toggle="modal" data-target="#delete_confirm_modal"> <span class="far fa-trash-alt" aria-hidden="true"></span> Delete</button>
							</div>
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

	<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="confirmation_title">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="confirmation_title">Confirm Information</h4>
	      </div>
	      <div class="modal-body">
	      </div>
	      <div class="modal-footer">
        	<button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="delete_confirm_modal" tabindex="-1" role="dialog" aria-labelledby="delete_confirmation_title">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="delete_confirmation_title">Confirm Deletion</h4>
	      </div>
	      <div class="modal-body">
					<p>
						Are you sure you want to remove <span id="delete_name"></span> from your guardians?
					</p>
	      </div>
	      <div class="modal-footer">
					<form id="delete-form" method="POST">
						{!! csrf_field() !!}
						{!! method_field("DELETE") !!}
        		<button type="submit" class="btn btn-danger"><span id="far fa-trash-alt"></span> Delete</button>
					</form>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="row">
		<div class="col-md-6 col-md-offset-6 col-xs-12">
			<div class="btn-toolbar three">
				<a class="btn btn-lg btn-success pull-right" href="{{action('StudentInformationController@index')}}"><span class="fas fa-save" aria-hidden="true"></span> <span>Save and Continue</span> </a>
				<a class="btn btn-lg btn-info pull-right"    href="{{action('StudentInformationController@individualGuardian') }} "><span class="far fa-plus" aria-hidden="true"></span> <span>New Parent/Guardian</span></a>
    		<a class="btn btn-lg btn-danger pull-right"  href="{{action('StudentInformationController@index')}}"><span class="far fa-times" aria-hidden="true"></span> <span>Cancel</span></a>
			</div>
		</div>
	</div>
@endsection
