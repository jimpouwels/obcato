// initialize event handlers
$(document).ready(function() {
	// add image button
	$('#add_webform').click(function() {
		$('#add_webform_action').attr('value', 'add_webform');
		$('#add_form_hidden').submit();
		return false;
	});
});