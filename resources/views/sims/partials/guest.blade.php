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
            <label name="relationship">Relationship to new maroon</label>
            <select name="relationship" class="form-control">
              <option value="" disabled hidden selected>-Please select an option-</option>
              <option value="0">Parent/Guardian</option>
              <option value="1">Sibling</option>
              <option value="2">Grandparent</option>
              <option value="3">Friend</option>
              <option value="4">Other</option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-4">
            <label name="n*_first_name">First Name</label>
            <input name="n*_first_name" type="text" class="form-control" />
          </div>
          <div class="col-md-4">
            <label name="n*_last_name">Last Name</label>
            <input name="n*_last_name" type="text" class="form-control"  />
          </div>
          <div class="col-md-4">
            <label name="n*_email">Email</label>
            <input name="n*_email" type="text" class="form-control"  />
          </div>
        </div>
      </div>
      {{-- Dietary Needs --}}
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label name="n*_has_dietary_needs">Do they have any special dietary needs (vegetarian, allergies, vegan, etc.)?</label><br>
            <input type="radio" name="n*_has_dietary_needs" value="yes" required>&nbsp;<label>Yes</label>&nbsp;
            <input type="radio" name="n*_has_dietary_needs" value="no">&nbsp;<label>No</label>
          </div>
          <div class="col-md-6" id="n*_dietary_needs" hidden>
            <label name="n*_dietary_needs">Please describe dietary needs.</label>
            <textarea name="n*_dietary_needs" class="form-control" ></textarea>
          </div>
        </div>
      </div>
      {{-- Physical Needs --}}
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label name="n*_has_physical_needs">Do they have any physical needs which need accommodating?</label><br>
            <input type="radio" name="n*_has_physical_needs" value="yes" required>&nbsp;<label>Yes</label>&nbsp;
            <input type="radio" name="n*_has_physical_needs" value="no">&nbsp;<label>No</label>
          </div>
          <div class="col-md-6" id="n*_physical_needs" hidden>
            <label name="n*_physical_needs">Please describe physical needs</label>
            <textarea name="n*_physical_needs" class="form-control" ></textarea>
          </div>
        </div>
      </div>
      {{-- Stay On Campus? --}}
      <div class="form-group">
        <div class="row">
          <div class="col-md-12">
            <label name="n*_on_campus" >Do they wish to stay on campus?</label><br>
            <input type="radio" name="n*_on_campus" value="yes" required>&nbsp;<label>Yes</label>&nbsp;
            <input type="radio" name="n*_on_campus" value="no">&nbsp;<label>No</label>
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
