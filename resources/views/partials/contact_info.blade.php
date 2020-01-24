<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="form-group">
			<label for="contact_name">
				Contact Name <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
			</label>
			<input type="text" class="form-control" name="contact_name" id="contact_name" @if(!empty($contact)) value="{{$contact->name}}" @endif>
		</div>
	</div>

	<div class="col-xs-12 col-md-6">
		<div class="form-group">
			<label for="relationship">
				Relationship <span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>
			</label>
			<input type="text" class="form-control" name="relationship" id="relationship" @if(!empty($contact)) value="{{$contact->relationship}}" @endif>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-4">
		<div class="form-group">
			<label for="daytime_phone">
				Daytime Phone
			</label>
			<input type="tel" class="form-control" name="daytime_phone" id="daytime_phone" pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" @if(!empty($contact)) value="{{$contact->day_phone}}" @endif>
		</div>
	</div>

	<div class="col-xs-12 col-md-4">
		<div class="form-group">
			<label for="evening_phone">
				Evening Phone
			</label>
			<input type="tel" class="form-control" name="evening_phone" id="evening_phone" pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" @if(!empty($contact)) value="{{$contact->evening_phone}}" @endif>
		</div>
	</div>

	<div class="col-xs-12 col-md-4">
		<div class="form-group">
			<label for="cell_phone">
				Cell Phone
			</label>
			<input type="tel" class="form-control" name="cell_phone" id="cell_phone" pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" @if(!empty($contact)) value="{{$contact->cell_phone}}" @endif>
		</div>
	</div>
</div>
