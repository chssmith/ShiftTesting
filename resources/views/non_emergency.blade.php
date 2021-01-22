@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Non-Emergency Contact
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

	<style>
		label{
			font-size:20px;
		}

		.panel-body {
			font-size: 24px;
			line-height: 22pt;
		}

		hr {
			margin-bottom: 20px;
		}
	</style>
@endsection

@section("content")
	<form id="nonEmergencyInfo" method="POST"
		action="{{ action("StudentInformationController@nonEmergencyUpdate") }}">
		{{ csrf_field() }}

		<div class = "row">
			<div class="col-xs-12">
				<div class="panel">
					<div class="panel-body">
						<p>
							Occasionally, Roanoke College uses automated text messages to
							reach students for non-emergency purposes.
						</p>
						<p style="margin-bottom: 0px;">
							For example, reminder
							messages are sent about Health Services appointments, intramurals,
							and course evaluations.
						</p>
					</div>
				</div>
     		<div>
					<div class="pretty p-default">
						<input type="checkbox" name="non_emergency" value="non_emergency" id="non_emergency" @if($student->non_emergency || is_null($student->non_emergency)) checked @endif />
							<div class="state p-primary">
								<label>I wish to recieve these automated messaged</label>
							</div>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<div class = "row">
			<div class="col-xs-12">
				<div class="panel">
					<div class="panel-body">
						<p>
							Roanoke College occasionally takes photographs and audio/video
							recordings of the campus, classrooms, and the general student
							population at events, gatherings and daily routines. As a student at
							Roanoke College, I understand that I may appear, either on purpose
							or by chance, in these images and videos.
						</p>

						<p>
							I grant Roanoke College permission to record or photograph me and
							thereafter to use the photographs, video and audio recordings in
							whole or in part without restriction anywhere, in any medium, for
							any purpose and altered in any way.
						</p>

						<p>
							I also release Roanoke College from all claims of liability
							relating to the use of the photographs, video or audio recordings.
						</p>

						<p>
							This Permissions and Rights Release shall be irrevocable and binding
							upon my successors, legal representatives, and assigns and shall
							accrue to the benefit of Roanoke College, its legal representatives,
							and its assigns.
						</p>
					</div>
				</div>
     		<div>
					<div class="pretty p-default">
						<input type="checkbox" name="photo_consent" value="photo_consent" id="photo_consent" @if($student->photo_consent) checked @endif />
							<div class="state p-primary">
								<label>I allow Roanoke College permission to photograph or record me in any way</label>
							</div>
					</div>
				</div>
			</div>
		</div>

		 <div class = "row">
			 <div class="col-xs-12">
				 <div class="btn-toolbar">
					 <button type = "submit" class = "btn btn-lg btn-success pull-right"> Save and Continue </button>
					 <a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
				 </div>
			 </div>
		 </div>
	 </form>
@endsection
