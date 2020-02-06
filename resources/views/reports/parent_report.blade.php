<style>
table.gridtable {
		font-family: verdana,arial,sans-serif;
		font-size:13px;
		color:#333333 ;
		border-width: 1px;
		border-color: #666666 ;
		border-collapse: collapse;
}
table.gridtable th {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #666666 ;
		background-color: #dedede ;
}
table.gridtable td {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #666666 ;
		background-color: #ffffff ;
}

.grayed {
	background-color: gray !important;
}



	/** Define the margins of your page **/
	@page {
			margin: 100px 25px;
	}

	header {
			position: fixed;
			top: -90px;
			left: 0px;
			right: 0px;
			height: 50px;
			line-height: 35px;
	}

</style>
<body>
	<?php $color = "white"; ?>
	<header>
		<p  style="text-align:right; margin-bottom:0px; margin-top:0px;"> Student ID: {{$student->RCID}} </p>
		<p  style="text-align:right; margin-bottom:0px; margin-top:0px;"> Date Changed: {{ date('m/j/y', strtotime($student->updated_at)) }}</p>
	</header>
	<h1 style="text-align:right; margin-bottom:0px; margin-top:5px;"> Parent Information </h1>
	<hr style="border-width:2px;">
	@foreach($student->parents as $parent)
		<h3> Demographics </h3>
		{{-- Colorless Infobox --}}
		<table width="100%" class="gridtable" border="1">
			<thead>
				<tr>
					<th width="25%">
						Field Name
					</th>

					<th width="25%">
						Original
					</th>

					<th width="25%">
						New
					</th>

					<th width="25%">
						Field Changed?
					</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td> First Name </td>
					<td> @if(!empty($parent->ods_guardian)) {{ $parent->ods_guardian->FIRST_NAME }} @endif</td>
					<td> {{ $parent->first_name }} </td>
					<td>
						@if(!empty($parent->ods_guardian) && $parent->ods_guardian->FIRST_NAME != $parent->first_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Middle Name </td>
					<td> @if(!empty($parent->ods_guardian)) {{ $parent->ods_guardian->MIDDLE_NAME }} @endif</td>
					<td> {{ $parent->middle_name }} </td>
					<td>
						@if(!empty($parent->ods_guardian) && $parent->ods_guardian->MIDDLE_NAME != $parent->middle_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Last Name </td>
					<td> @if(!empty($parent->ods_guardian)) {{ $parent->ods_guardian->LAST_NAME }} @endif </td>
					<td> {{ $parent->last_name }} </td>
					<td>
						@if(!empty($parent->ods_guardian) && $parent->ods_guardian->LAST_NAME != $student->last_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Nickname </td>
					<td> @if(!empty($parent->ods_guardian)) {{ $parent->ods_guardian->nickname }} @endif </td>
					<td>
						{{ $parent->nick_name }}
					 </td>
					<td>
						@if(!empty($parent->ods_guardian) && $parent->ods_guardian->nickname != $parent->nickname)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Marital Status </td>
					<td> @if(!empty($parent->ods_guardian)) {{ $parent->ods_guardian->fkey_marital_status }} @endif </td>
					<td>
						{{ $parent->fkey_marital_status }}
					</td>
					<td>
						@if(!empty($parent->ods_guardian) && $parent->ods_guardian->fkey_marital_status != $parent->fkey_marital_status)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Dependent Status </td>
					<td>
					</td>
					<td>
						{{ ($parent->claimed_dependent ? 'T' : '') }}
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td> Residing With </td>
					<td class="grayed">
					</td>
					<td>
						{{ ($parent->reside_with ? 'Y' : 'N') }}
					</td>
					<td class="grayed">
					</td>
				</tr>
				<tr>
					<td> Relationship </td>
					<td class="grayed">
					</td>
					<td>
						@if ($parent->relationship != 'O')
							{{ $parent->guardian_type->type }}
						@else
							{{ $parent->relationship->other }}
						@endif
					</td>
					<td class="grayed">
					</td>
				</tr>
			</tbody>
		</table>

		<h3> Addresses </h3>
		{{-- Colorless Infobox --}}
		<table width="100%" class="gridtable" border="1" style="page-break-after:always;">
			<thead>
				<tr>
					<th width="25%">
						Field Name
					</th>

					<th width="25%">
						Original
					</th>

					<th width="25%">
						New
					</th>

					<th width="25%">
						Field Changed?
					</th>
				</tr>
			</thead>
			@php
				$address     = $parent->ods_guardian;
			@endphp
			<tbody>
				<tr>
					<th> Home Address  </th>
					<th colspan=3 class="grayed"> </th>
				</tr>

				<tr>
					<td> Street 1 </td>
					<td>
						@if(!empty($address))
							{{ $address->Address1 }}
						@endif
					</td>
					<td>
						@if (!empty($parent))
							{{ $parent->Address1 }}
						@endif
					</td>
					<td>
						@if(!empty($address) &&  (empty($parent) || $parent->Address1 != $address->Address1))
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Street 2 </td>
					<td>
						@if(!empty($address))
							{{ $address->Address2 }}
						@endif
					</td>
					<td>
						@if (!empty($parent))
							{{ $parent->Address2 }}
						@endif
					</td>
					<td>
						@if(!empty($address) && (empty($parent) || $parent->Address2 != $address->Address2))
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> City </td>
					<td>
						@if(!empty($address))
							{{ $address->CITY }}
						@endif
					</td>
					<td>
						@if (!empty($parent))
							{{ $parent->City }}
						@endif
					</td>
					<td>
						@if(!empty($address) && (empty($parent) || $parent->City != $address->CITY))
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> State </td>
					<td>
						@if(!empty($address))
							{{ $address->fkey_StateCode }}
						@endif
					</td>
					<td>
						@if (!empty($parent))
							{{ $parent->fkey_StateCode }}
						@endif
					</td>
					<td>
						@if(!empty($address) && (empty($parent) || $parent->fkey_StateCode != $address->fkey_StateCode))
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Zip Code </td>
					<td>
						@if(!empty($address))
							{{ $address->PostalCode }}
						@endif
					</td>
					<td>
						@if(!empty($parent))
							{{ $parent->PostalCode }}
						@endif
					</td>
					<td>
						@if(!empty($address) && (empty($parent) || $parent->PostalCode != $address->PostalCode))
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Country </td>
					<td>
						@if(!empty($address->country))
							{{ $address->country->CountryCode }}
						@endif
					</td>
					<td>
						@if(!empty($parent->country))
							{{ $parent->country->CountryCode }}
						@endif
					</td>
					<td>
						@if(!empty($address) && (empty($parent) || $parent->fkey_CountryId != $address->fkey_CountryId))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<th>Joint Mailing</th>
					<th colspan="3" class="grayed"></th>
				</tr>
				<tr>
					<td>Joint Mailing 1</td>
					<td>
						@if (!empty($parent->ods_guardian))
							{{ $parent->ods_guardian->joint_mail_1 }}
						@endif
					</td>
					<td>
						{{ $parent->joint_mail1 }}
					</td>
					<td>
						@if(empty($parent->ods_guardian) || $parent->ods_guardian->joint_mail_1 != $parent->joint_mail1)
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>Joint Mailing 2</td>
					<td>
						@if (!empty($parent->ods_guardian))
							{{ $parent->ods_guardian->joint_mail_2 }}
						@endif
					</td>
					<td>
						{{ $parent->joint_mail2 }}
					</td>
					<td>
						@if(empty($parent->ods_guardian) || $parent->ods_guardian->joint_mail_2 != $parent->joint_mail2)
							Y
						@endif
					</td>
				</tr>
			</tbody>
		</table>
		<h3> Business </h3>
		<table width="100%" class="gridtable" border="1" style="page-break-after:always;">
			<thead>
				<tr>
					<th width="25%">
						Field Name
					</th>

					<th width="25%">
						Original
					</th>

					<th width="25%">
						New
					</th>

					<th width="25%">
						Field Changed?
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						Name
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->employer_name }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->employer_name }}
						@endif
					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->employer_name != $parent->ods_guardian->employment->employer_name))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						Position
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->position }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->position }}
						@endif
					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->employer_name != $parent->ods_guardian->employment->employer_name))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						Street 1
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->Street1 }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->Street1 }}
						@endif
					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->Street1 != $parent->ods_guardian->employment->Street1))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						Street 2
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->Street2 }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->Street2 }}
						@endif

					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->Street2 != $parent->ods_guardian->employment->Street2))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						City
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->city }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->city }}
						@endif

					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->city != $parent->ods_guardian->employment->city))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						State
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->fkey_StateCode }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->fkey_StateCode }}
						@endif
					</td>
					<td>
						@if(!empty($parent->employment->fkey_StateCode) && (empty($parent->ods_guardian->employment) || $parent->employment->fkey_StateCode != $parent->ods_guardian->employment->fkey_StateCod))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						Zip Code
					</td>
					<td>
						@if (!empty($parent->ods_guardian->employment))
							{{ $parent->ods_guardian->employment->postal_code }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment))
							{{ $parent->employment->postal_code }}
						@endif
					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->postal_code != $parent->ods_guardian->employment->postal_code))
							Y
						@endif
					</td>
				</tr>
				<tr>
					<td>
						Country
					</td>
					<td>
						@if (!empty($parent->ods_guardian->country))
							{{ $parent->ods_guardian->employment->country->CountryCode }}
						@endif
					</td>
					<td>
						@if (!empty($parent->employment->country))
							{{ $parent->employment->country->CountryCode }}
						@endif
					</td>
					<td>
						@if(!empty($parent->employment) && (empty($parent->ods_guardian->employment) || $parent->employment->fkey_CountryId != $parent->ods_guardian->employment->fkey_CountryId))
							Y
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	@endforeach

</body>
