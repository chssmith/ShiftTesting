@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Independent Student
@endsection

@section("stylesheets")
	@parent
	<link rel="stylesheet" type="text/css" href="{{ asset("css/global.css") }}" />
	<style>
		label{
			font-size:20px;
		}

		ul{
			list-style-type:square;
			padding-left:25px;
		}

		.panel-body ul > li{
			padding: 10px 0px;
		}

		.panel-body ul > li:not(:last-child)::after {
			/* content: "OR"; */
		}
	</style>
@endsection

@section("content")
	<form id="independentStudent" method="POST"
				action="{{ action("StudentInformationController@independentStudentUpdate") }}">
		{{ csrf_field() }}

		<div class="panel">
			<div class="panel-body">
				<strong> In order to be considered an independent student, you must be able to answer <em>yes</em> to one of the following questions: </strong>
				<ul>
					<li>
						Were you born before Jan. 1, {{ \Carbon\Carbon::now()->subYears(23)->format("Y") }}?
					</li>

					<li>
						As of today, are you married? (Also answer “Yes” if you are
						separated but not divorced.)
					</li>

					<li>
						At the beginning of the 2020–21 school year, will you be working on
						a master’s or doctorate program (such as an M.A., MBA, M.D., J.D.,
						Ph.D., Ed.D., graduate certificate, etc.)?
					</li>

					<li>
						Are you currently serving on active duty in the U.S. armed forces
						for purposes other than training? (If you are a National Guard or
						Reserves enlistee, are you on active duty for other than state or
						training purposes?)
					</li>

					<li>
						Are you a veteran of the U.S. armed forces?
					</li>

					<li>
						Do you now have—or will you have—children who will receive more than
						half of their support from you between July 1, 2020, and June 30,
						2021?
					</li>

					<li>
						Do you have dependents (other than your children or spouse) who live
						with you and who receive more than half of their support from you,
						now and through June 30, 2021?
					</li>

					<li>
						At any time since you turned age 13, were both your parents
						deceased, were you in foster care, or were you a dependent or ward
						of the court?
					</li>

					<li>
						Has it been determined by a court in your state of legal residence
						that you are an emancipated minor or that someone other than your
						parent or stepparent has legal guardianship of you? (You also should
						answer "Yes" if you are now an adult but were in legal guardianship
						or were an emancipated minor immediately before you reached the age
						of being an adult in your state. Answer "No" if the court papers say
						"custody" rather than "guardianship.")
					</li>

					<li>
						At any time on or after July 1, 2019, were you determined to be an
						unaccompanied youth who was homeless or were self-supporting and at
						risk of being homeless, as determined by (a) your high school or
						district homeless liaison, (b) the director of an emergency shelter or
						transitional housing program funded by the U.S. Department of Housing
						and Urban Development, or (c) the director of a runaway or homeless
						youth basic center or transitional living program?**
					</li>

				</ul>
			</div>
		</div>

		<div>
			<div class="pretty p-default">
				<input type="checkbox" name="independent_student" value="independent_student" id="independent_student" @if($student->independent_student) checked @endif />
				<div class="state p-primary">
					<label>I am an Independent Student.</label>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-xs-12">
				<div class="btn-toolbar">
					<button type = "submit" class = "btn btn-lg btn-success pull-right"> Save and Continue </button>
					<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
				</div>
			</div>
		</div>
	</form>
@endsection
