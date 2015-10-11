$(document).ready(function() {
	// update block button
	$('#update_element_holder').click(function() {
		$('#action').attr('value', 'update_element_holder');
		$('#element_holder_form_id').submit();
	});
	
	// delete block button
	$('#delete_element_holder').click(function() {
		var confirmed = confirm("Weet u zeker dat u dit blok wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_block');
			$('#element_holder_form_id').submit();
		} else {
			return false;
		}
	});
	
	// add block button
	$('#add_element_holder').click(function() {
		$('#add_block_action').attr('value', 'add_block');
		$('#add_form_hidden').submit();
	});
	
	// update position button
	$('#update_position').click(function() {
		$('#action').attr('value', 'update_position');
		$('#position_form').submit();
	});

	// add position
	$('#add_position').click(function() {
		$('#add_position_form').submit();
	});
	
	// delete position button
	$('#delete_positions').click(function() {
		var $checked = false;
		$('input:checkbox').each(function() {
			if ($(this).attr('checked')) {
				$checked = true;
			}
		});
		if (!$checked) {
			alert('U heeft geen posities geselecteerd');
		} else {
			var confirmed = confirm('Weet u zeker dat u de geselecteerde posities wilt verwijderen?');
			if (confirmed) {
				$('#position_delete_action').attr('value', 'delete_positions');
				$('#positions_delete_form').submit();
			}
		}
	});
});