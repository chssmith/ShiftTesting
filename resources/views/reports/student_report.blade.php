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


<body id="body{{$student->RCID}}" class="body">
	<header id="header{{$student->RCID}}" class="header">
		<p style="text-align:right; margin-bottom:0px; margin-top:0px;"> Student ID: {{$student->RCID}} </p>
		<p style="text-align:right; margin-bottom:0px; margin-top:0px;"> Date Changed: {{ date('m/j/y', strtotime($student->updated_at)) }} </p>
	</header>

	<main>
		<?php $color = "white";
			  $rcid = $student->RCID;
		 ?>
		<h1 style="text-align:right; margin-bottom:0px; margin-top:5px;"> Student Information </h1>
		<hr style="border-width:2px;">

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
					<td>  </td>
					<td> {{ $student->first_name }} </td>
					<td>
						@if(null != $student->first_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Middle Name </td>
					<td>  </td>
					<td> {{ $student->middle_name }} </td>
					<td>
						@if(null != $student->middle_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Last Name </td>
					<td>  </td>
					<td> {{ $student->last_name }} </td>
					<td>
						@if(null != $student->last_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Maiden Name </td>
					<td>  </td>
					<td> {{ $student->maiden_name }} </td>
					<td>
						@if(null != $student->maiden_name)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Green Card </td>
					<td>  </td>
					<td>
						@if( $student->green_card )
							True
						@endif
					 </td>
					<td>
						@if(null != $student->green_card)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Visa </td>
					<td>  </td>
					<td> @if(!empty($student->visa)) Y @endif </td>
					<td>
						@if(!empty($student->visa) && null != $student->visa)
							Y
						@endif
					</td>
				</tr>

				<tr>
					<td> Independence </td>
					<td>  </td>
					<td> @if($student->independent_student)
							Y
						 @elseif(!$student->independent_student)
						 	N
						 @endif </td>
					<td>
						@if(null != $student->independent_student)
							Y
						@endif
					</td>
				</tr>
			</tbody>
		</table>

		<h3> Addresses </h3>
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
				@foreach($student->address as $type => $address)
					<tr>
						<td> <strong> {{$type}} </strong> </td>
						<td class="grayed"> </td>
						<td class="grayed"> </td>
						<td class="grayed"> </td>
					</tr>

					<tr>
						<td> Street 1 </td>
						<td>  </td>
						<td>
							@if(!empty($address))
								{{ $address->Address1 }}
							@endif
						</td>
						<td>
							@if(!empty($address) && null != $address->Address1)
								Y
							@endif
						</td>
					</tr>

					<tr>
						<td> Street 2 </td>
						<td>  </td>
						<td>
							@if(!empty($address))
								{{ $address->Address2 }}
							@endif
						</td>
						<td>
							@if(!empty($address) && null != $address->Address2)
								Y
							@endif
						</td>
					</tr>

					<tr>
						<td> City </td>
						<td>  </td>
						<td>
							@if(!empty($address))
								{{ $address->City }} </td>
							@endif
						<td>
							@if(!empty($address) && null != $address->City)
								Y
							@endif
						</td>
					</tr>

					<tr>
						<td> State </td>
						<td>  </td>
						<td>
							@if(!empty($address))
								{{ $address->fkey_StateId }} </td>
							@endif
						<td>
							@if(!empty($address) && null != $address->fkey_StateId)
								Y
							@endif
						</td>
					</tr>

					<tr>
						<td> Zip Code </td>
						<td>  </td>
						<td>
							@if(!empty($address))
								{{ $address->PostalCode }}
							@endif
						</td>
						<td>
							@if(!empty($address) &&null != $address->PostalCode)
								Y
							@endif
						</td>
					</tr>

					<tr>
						<td> Country </td>
						<td>  </td>
						<td>
							@if(!empty($address))
								{{ $address->fkey_CountryId }}
							@endif
						</td>
						<td>

							@if(!empty($address) && null != $address->fkey_CountryId)
								Y
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</main>
</body>
