@extends('forms_template')

@section('javascript')
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
				<th class="hidden-xs hidden-sm">Verified</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($guardians[0]))
				@foreach($guardians as $guardian)
					<tr>
						<td>{{$guardian->first_name . " " . $guardian->last_name}}</td>
						<td class="hidden-xs hidden-sm">{{$guardian->relationship}}</td>
						<td class="hidden-xs hidden-sm"> No </td>
						<td>
							<a href="{{action('StudentInformationController@individualGuardian', ['id'=>$guardian->id])}}" class="btn btn-primary"><i class="far fa-edit" aria-hidden="true"></i> Edit</a>
							<a href="{{action('StudentInformationController@deleteGuardian', ['id'=>$guardian->id])}}" class="btn btn-danger"> <i class="far fa-trash-alt" aria-hidden="true"></i> Delete</a>
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

	<div class="row">
		<div class="col-md-6 col-md-offset-6 col-xs-12">
			<div class="btn-toolbar three">
				<a class="btn btn-lg btn-success pull-right" href="{{action('StudentInformationController@index')}}"><span class="fas fa-save" aria-hidden="true"></span> <span>Save and Continue</span> </a>
				<a class="btn btn-lg btn-info pull-right"    href="{{action('StudentInformationController@individualGuardian') }} "><span class="far fa-plus" aria-hidden="true"></span> <span>New Parent/Guardian</span></a>
    		<a class="btn btn-lg btn-danger pull-right"  href="{{action('StudentInformationController@index')}}" ><span class="far fa-times" aria-hidden="true"></span> <span>Cancel</span></a>
			</div>
		</div>
	</div>
@endsection
