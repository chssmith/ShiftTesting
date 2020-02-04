<div id="guest_temp" hidden>
  <div class="panel" class="guest" id="n*">
    <div class="panel-heading">
      <h4>Guest</h4>
    </div>
    {{-- Personal Info --}}
    <div class="panel-body">
      <div class="form-group">
        <div class="row">
          <div class="col-md-12">
            <label name="relationship[]">Relationship to new maroon</label>
            <input name="relationship[]" type="text" class="form-control" id="n*_relationship" required />
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-4">
            <label name="first_name[]">First Name</label>
            <input name="first_name[]" type="text" class="form-control" id="n*_first_name" required />
          </div>
          <div class="col-md-4">
            <label name="last_name[]">Last Name</label>
            <input name="last_name[]" type="text" class="form-control" id="n*_last_name" required />
          </div>
          <div class="col-md-4">
            <label name="email[]">Email</label>
            <input name="email[]" type="email" class="form-control" id="n*_email" required />
          </div>
        </div>
      </div>
      {{-- Dietary Needs --}}
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label name="has_dietary_needs[]">Do they have any special dietary needs (vegetarian, allergies, vegan, etc.)?</label><br>
            <label><input name="has_dietary_needs[]" type="checkbox" class="n*_has_dietary_needs" value="yes">&nbsp;Yes</label>&nbsp;
            <input name="has_dietary_needs[]" type="checkbox" id="n*_has_dietary_needs" value="no" checked hidden>
          </div>
          <div class="col-md-6" id="n*_dietary_needs" hidden>
            <label name="dietary_needs[]">Please describe dietary needs.</label>
            <textarea name="dietary_needs[]" class="form-control" ></textarea>
          </div>
        </div>
      </div>
      {{-- Physical Needs --}}
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label name="has_physical_needs[]">Do they have any physical needs which need accommodating?</label><br>
            <label><input name="has_physical_needs[]" type="checkbox" class="n*_has_physical_needs" value="yes">&nbsp;Yes</label>&nbsp;
            <input name="has_physical_needs[]" type="checkbox" id="n*_has_physical_needs" value="no" checked hidden>
          </div>
          <div class="col-md-6" id="n*_physical_needs" hidden>
            <label name="physical_needs[]">Please describe physical needs</label>
            <textarea name="physical_needs[]" class="form-control" ></textarea>
          </div>
        </div>
      </div>
      {{-- Stay On Campus? --}}
      <div class="form-group">
        <div class="row">
          <div class="col-md-12">
            <label name="on_campus[]" >Do they wish to stay on campus?</label><br>
            <label><input name="on_campus[]" type="checkbox" class="n*_on_campus" value="yes">&nbsp;Yes</label>&nbsp;
            <input name="on_campus[]" type="checkbox" id="n*_on_campus" value="no" checked hidden>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="pull-right btn btn-md btn-danger remove" data="n*">Remove</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
