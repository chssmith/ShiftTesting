@if(\Session::has("message"))
  <div class="row">
    <div class="col-xs-12">
      <div class="alert alert-warning light">
        <span class="fas fa-exclamation-triangle" style="padding-right: 20px;"></span>
        {{ \Session::get("message") }}
      </div>
    </div>
  </div>
@endif
