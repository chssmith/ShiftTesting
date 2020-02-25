<div id="additional_forms" class="list-group">
  @foreach($additional_forms as $additional_form)
    @if (!empty($additional_form->link))
      @php
        $completed = $percs->contains($additional_form->getPerc());
      @endphp
      <a @if(!$completed)href="{{ ($additional_form->internal ? action($additional_form->link) : $additional_form->link) }}"@endif class="list-group-item">
        <link-title>{{ $additional_form->title }}</link-title>
        <due-date>Due: {{ $additional_form->due_date->format("n/j/Y") }}</due-date>
        <badge>
          @if ($completed)
            @include("partials.complete_badge")
          @endif
        </badge>
      </a>
    @else
      @include("partials.collapsing_panel", ["postfix" => $additional_form->getPerc(),
                                             "title" => $additional_form->title,
                                             "content" => $additional_form->accordion_text
                                            ])
    @endif
  @endforeach
</div>
