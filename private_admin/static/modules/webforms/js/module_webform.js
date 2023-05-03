// initialize event handlers
$(document).ready(function() {
	// add webform button
	$('#add_webform').click(function() {
		$('#add_webform_action').attr('value', 'add_webform');
		$('#add_form_hidden').submit();
		return false;
	});

	// update webform button
	$('#update_webform').click(function() {
		$('#action').attr('value', 'update_webform');
		$('#webform-editor-form').submit();
	});
});

function addFormField(webform_id, type) {
	$('#action').attr('value', 'add_textfield');
	$('#webform-editor-form').submit();
}