/*
	Author: Jim Pouwels
	Date: June 11th, 2011
*/

// initialize event handlers
$(document).ready(function() {
	// apply button
	$('#update_element_holder').click(function() {
		$('#action').attr('value', 'update_element_holder');
		$('#element_holder_form_id').submit();
	});
	
	// delete button
	$('#delete_element_holder').click(function() {
		var confirmed = confirm("Weet u zeker dat u deze, en alle onderliggende pagina's, wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_page');
			$('#element_holder_form_id').submit();
		} else {
			return false;
		}
	});
	
	// add button
	$('#add_element_holder').click(function() {
		$('#action').attr('value', 'sub_page');
		$('#element_holder_form_id').submit();
	});
	
	// move up button
	$('#moveup_element_holder').click(function() {
		$('#action').attr('value', 'move_up');
		$('#element_holder_form_id').submit();
	});
	
	// move down button
	$('#movedown_element_holder').click(function() {
		$('#action').attr('value', 'move_down');
		$('#element_holder_form_id').submit();
	});
});