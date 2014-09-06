/*
	Fix for submitting the login form by
	hitting the "Enter" key
*/
$(document).ready(function() {
	$('.admin_field').each(function() {
		$(this).keypress(function(e) {
			if (e.keyCode == 13) {
				$('#form-login').submit();
			}
		});
	});
});