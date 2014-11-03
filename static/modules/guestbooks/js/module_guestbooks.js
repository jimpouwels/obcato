// initialize event handlers
$(document).ready(function() {
	// apply button
	$('#update_guestbook').click(function() {
		$('#action').attr('value', 'update_guestbook');
		$('#guestbook_form').submit();
	});
	
	// delete button
	$('#delete_guestbook').click(function() {
		var confirmed = confirm("Weet u zeker dat u dit gastenboek en alle bijbehorende berichten wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_guestbook');
			$('#guestbook_form').submit();
		} else {
			return false;
		}
	});
	
	// add button
	$('#add_guestbook').click(function() {
		$('#action').attr('value', 'add_guestbook');
		$('#guestbook_form').submit();
	});
});