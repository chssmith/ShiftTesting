<div class="panel-heading list-group-item" role="tab" id="heading{{$postfix}}">
  <h4 class="panel-title">
    <a role="button" class="accordian-button" target="{{$postfix}}Content" style="color:black" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$postfix}}" aria-expanded="false" aria-controls="collapse{{$postfix}}">
      <div>
        <span id="{{$postfix}}Content" class="accordian-circle fas fa-chevron-circle-down fa-lg pull-right check" style="color: grey"></span>
        {{ $title }}
      </div>
    </a>
  </h4>
  <div id="collapse{{$postfix}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$postfix}}">
    <div class="panel-body" style="margin-top: 10px;">
      {{ $content }}
    </div>
  </div>
</div>
