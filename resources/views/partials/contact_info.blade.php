<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class = "form-group">
	        Contact Name: <input type= "text" class = "form-control" name = "contact_name" id = "contact_name"  
	        				@if(!empty($contact)) value="{{$contact->name}}" @endif>
		</div>
	</div>

	<div class="col-xs-12 col-md-6">
		<div class = "form-group">
	        Relationship: <input type= "text" class = "form-control" name = "relationship" id = "relationship"  
	        				@if(!empty($contact)) value="{{$contact->relationship}}" @endif>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-4">
		<div class = "form-group">
	        Daytime Phone: <input type= "tel" class = "form-control" name = "daytime_phone" id = "daytime_phone" 
	        					  pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$"
	        				@if(!empty($contact)) value="{{$contact->day_phone}}" @endif>
		</div>
	</div>

	<div class="col-xs-12 col-md-4">
		<div class = "form-group">
	        Evening Phone: <input type= "tel" class = "form-control" name = "evening_phone" id = "evening_phone" 
	        					  pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" 
	        				@if(!empty($contact)) value="{{$contact->evening_phone}}" @endif>
		</div>
	</div>

	<div class="col-xs-12 col-md-4">
		<div class = "form-group">
	        Cell Phone: <input type= "tel" class = "form-control" name = "cell_phone" id = "cell_phone"  
	        				   pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" 
	        				@if(!empty($contact)) value="{{$contact->cell_phone}}" @endif>
		</div>
	</div>
</div>

