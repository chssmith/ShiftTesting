@if(\Session::has("message"))
  <div class="row">
    <div class="col-xs-12">
      <div class="alert alert-warning light">
        {{ \Session::get("message") }}
      </div>
    </div>
  </div>
@endif
