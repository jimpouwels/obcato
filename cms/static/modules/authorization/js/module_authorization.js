/*
	Author: Jim Pouwels
	Date: October 30th, 2011
*/

// initialize event handlers
$(document).ready(function() {
	// apply button
	$('#update_user').click(function() {
		$('#action').attr('value', 'update_user');
		$('#user_form').submit();
	});
	
	// delete button
	$('#delete_user').click(function() {
		var confirmed = confirm("Weet u zeker dat u deze gebruiker wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_user');
			$('#user_form').submit();
		} else {
			return false;
		}
	});
	
	// add button
	$('#add_user').click(function() {
		$('#action').attr('value', 'add_user');
		$('#user_form').submit();
	});
});