<div id="additional_forms" class="list-group">
  @foreach($additional_forms as $additional_form)
    @if (!empty($additional_form->link))
      <a href="{{$additional_form->link}}" class="list-group-item">
        <link-title>{{ $additional_form->title }}</link-title>
        <due-date>{{ $additional_form->due_date->format("n/j/Y") }}</due-date>
        <badge>
          @if (!$percs->where("perc", $additional_form->getPerc())->isEmpty())
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
