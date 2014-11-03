// initialize event handlers
$(document).ready(function() {
	
	// update download button
	$('#update_download').click(function() {
		$('#action').attr('value', 'update_download');
		$('#download-editor-form').submit();
	});
	
	// delete download button
	$('#delete_download').click(function() {
		var confirmed = confirm("Weet u zeker dat u deze download wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_download');
			$('#download-editor-form').submit();
		} else {
			return false;
		}
	});
	
	// add download button
	$('#add_download').click(function() {
		$('#add_download_action').attr('value', 'add_download');
		$('#add_form_hidden').submit();
	});
	
});