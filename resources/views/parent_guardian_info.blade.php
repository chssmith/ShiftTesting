@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Parent / Guardian Information
@endsection

@section("stylesheets")
	@parent

	<style>
		label{
			font-size:20px;
		}

		ul{
			list-style-type:disc;
			padding-left:25px;
		}

		table {
			margin-bottom:25px !important;
		}	
	</style>
@endsection

@section("content")
	<div class = "row">
     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel">
				<div class="panel-body">
					On the following screens, you will be asked to verify basic information about the parents and guardians you current have listed. For each parent or guardian, please make sure the information on each screen is correct. In order to complete this requirement, please make sure to do the following.

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
		</div>
	</div>

	<div class="row">
		<div class="col-md-12"> <table class="table table-striped no-margin">
				<thead>
					<tr>
						<th>Parent/Guardian Name</th>
						<th>Relationship</th>
						<th>Verified</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>

				<tbody>
					@if(!empty($guardians[0]))
						@foreach($guardians as $guardian)
							<tr>
								<td>{{$guardian->first_name . " " . $guardian->last_name}}</td>
								<td>{{$guardian->relationship}}</td>
								<td> No </td>
								<td> <a href="{{action('StudentInformationController@individualGuardian', ['id'=>$guardian->id])}}" class="btn btn-primary"><i class="fas fa-wrench"></i> Edit</a> </td>
								<td> <a href="{{action('StudentInformationController@deleteGuardian', ['id'=>$guardian->id])}}" class="btn btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
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

	<div class="row">
		<div class="col-md-12">
			<div class="btn-toolbar">
				<a class="btn btn-success pull-right" href="{{action('StudentInformationController@index')}}"> Continue </a>
				<a class="btn btn-info pull-right"    href="{{action('StudentInformationController@individualGuardian') }} "> New parent/guardian</a>
        		<a class="btn btn-danger pull-right"  href="{{action('StudentInformationController@index')}}" > Cancel </a>
			</div>
		</div>
	</div>
@endsection	

