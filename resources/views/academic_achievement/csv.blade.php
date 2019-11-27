RCID,{{ $headings->implode(",") }},
@foreach($students as $student)
{{ $student->RCID }},@foreach($headings as $heading){{(isset($student->courses[$heading]) ? 1 : 0)}},@endforeach 
@endforeach
