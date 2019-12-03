@extends('forms_template')

@section('heading')
	Academic Achievement Form
@endsection

@section('javascript')
	<script>
		function uncheck_div (div_id) {
			return function (evt) { $(div_id).find("input[type='checkbox']").attr("checked", false); }
		}
		$(document).ready( function () {
			$("#uncheck_AP").click(uncheck_div("#AP_exams"));
			$("#uncheck_IB").click(uncheck_div("#IB_exams"));
			$("#uncheck_DE").click(uncheck_div("#DE_courses"));
		});
	</script>
@endsection

@section("stylesheets")
	@parent
		<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

    <style>
      .exams {
        display: grid;
        grid-gap: 20px;
        grid-template-columns: 1fr 1fr 1fr;
      }
			button.btn.btn-primary {
				margin-bottom: 10px;
			}
			h3 {
				margin-top: 40px;
			}
			hr {
				margin-top: 40px;
			}
      @media (max-width: 1200px) {
        .exams {
          grid-template-columns: 1fr 1fr;
        }
      }
      @media (max-width: 767px) {
				h3 {
					margin-top: 20px;
				}
        .exams {
          grid-template-columns: 1fr;
          grid-gap: 10px;
        }
      }
    </style>
@endsection

@section("content")
  <form action="{{ action("StudentInformationController@storeAcademicAchievement") }}" method="POST">

    {{ csrf_field() }}

    <h3>Advanced Placement Examinations</h3>

    <div>
      <p>
        Please check any AP exams you have taken, or plan to take
      </p>
			<button type="button" id="uncheck_AP" class="btn btn-primary">Uncheck All</button>
      <div class="exams" id="AP_exams">
        @foreach ($ap_exams as $ap_exam)
          <label for="ap_{{ $ap_exam->id }}">
						<input type="checkbox" name="ap_exams[]" value="{{ $ap_exam->id }}" id="ap_{{ $ap_exam->id }}" @if (!empty($ap_exam->map) && !$ap_exam->map->isEmpty()) checked @endif/> {{ $ap_exam->name }}
					</label>
        @endforeach
      </div>
    </div>

		<hr />

    <h3>International Baccalaureate Examinations</h3>

    <div>
      <p>
        Please check any IB exams you have taken, or plan to take:
      </p>
			<button type="button" id="uncheck_IB" class="btn btn-primary">Uncheck All</button>
      <div class="exams" id="IB_exams">
        @foreach ($ib_exams as $ib_exam)
          <label for="ib_{{ $ib_exam->id }}">
						<input type="checkbox" name="ib_exams[]" value="{{ $ib_exam->id }}" id="ib_{{ $ib_exam->id }}" @if (!empty($ib_exam->map) && !$ib_exam->map->isEmpty()) checked @endif/> {{ $ib_exam->name }}
					</label>
        @endforeach
      </div>
    </div>

		<hr />

		<h3>Dual Enrollment Courses</h3>

    <div>
      <p>
        Please check any Dual Enrollment courses you have taken, or plan to take:
      </p>
			<button type="button" id="uncheck_DE" class="btn btn-primary">Uncheck All</button>
      <div class="exams" id="DE_courses">
        @foreach ($de_courses as $de_course)
          <label for="de_{{ $de_course->id }}">
						<input type="checkbox" name="de_courses[]" value="{{ $de_course->id }}" id="de_{{ $de_course->id }}" @if (!empty($de_course->map) && !$de_course->map->isEmpty()) checked @endif/> {{ $de_course->name }}
					</label>
        @endforeach
      </div>
    </div>

		<hr />

    <div class="row">
      <div class="col-md-10 col-sm-12" style="text-align: right;">
        <button type="submit" class="btn btn-complete btn-lg">Submit</button>
      </div>
    </div>
  </form>

@endsection
