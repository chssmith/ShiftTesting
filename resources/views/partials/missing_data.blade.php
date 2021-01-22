@foreach ($messages as $section => $section_messages)
  <h3>{{ $section }}</h3>
  <ul>
    @forelse ($section_messages as $message)
      <li>{{ $message }}</li>
    @empty
      <li>Section Complete</li>
    @endforelse
  </ul>
@endforeach
