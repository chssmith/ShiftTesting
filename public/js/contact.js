$("form").submit(function(){
	var day_phone     = $('#daytime_phone');
	var evening_phone = $('#evening_phone');
	var cell_phone    = $('#cell_phone');
	var contact_name  = $('#contact_name');
	var relationship  = $('#relationship');
	var warning       = $('#warning');
	if(day_phone.val().length === 0 && evening_phone.val().length === 0 && cell_phone.val().length === 0){
		day_phone.addClass('invalid');
		evening_phone.addClass('invalid');
		cell_phone.addClass('invalid');
		warning.show();
		warning.text('Please enter at least one contact phone number.');
		event.preventDefault();
	}
	if(contact_name.val().length === 0){
		contact_name.addClass('invalid');
		warning.show();
		warning.text("Please enter the contact's name ");
		event.preventDefault();				
	}
	if(relationship.val().length === 0){
		relationship.addClass('invalid');
		warning.show();
		warning.text('Please enter your relationship with the contact.');
		event.preventDefault();
	}
});