@extends('forms_template')

@section('heading')
	Emergency Contacts
@endsection

@section('javascript')
@endsection

@section("stylesheets")
	@parent
	<style>
		table {
			margin-bottom:25px !important;
		}	
	</style>
@endsection

@section("content")
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped no-margin">
				<thead>
					<tr>
						<th>Contact Name</th>
						<th>Relationship</th>
						<th>Daytime Phone</th>
						<th>Evening Phone</th>
						<th>Cell Phone</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>

				<tbody>
					@if(!empty($contacts[0]))
						@foreach($contacts as $contact)
							<tr>
								<td>{{$contact->name}}</td>
								<td>{{$contact->relationship}}</td>
								<td>{{$contact->day_phone}}</td>
								<td>{{$contact->evening_phone}}</td>
								<td>{{$contact->cell_phone}}</td>
								<td> <a href="{{action('StudentInformationController@individualEmergencyContact', ['id'=>$contact->id])}}" class="btn btn-primary"><i class="fas fa-wrench"></i> Edit</a> </td>
								<td> <a href="{{action('StudentInformationController@deleteGuardian', ['id'=>$contact->id])}}" class="btn btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
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
				<a href="{{action('StudentInformationController@missingPersonContact')}}" class="btn btn-lg btn-info pull-right"> Submit </a>
				<a href="{{action('StudentInformationController@individualEmergencyContact')}}" class="btn btn-lg btn-success pull-right"> New Contact </a>
			</div>
		</div>
	</div>
@endsection