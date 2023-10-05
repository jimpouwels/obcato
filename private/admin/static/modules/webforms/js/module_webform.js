// initialize event handlers
$(document).ready(function() {
	// add webform action button
	$('#add_webform').click(function() {
		$('#add_webform_action').attr('value', 'add_webform');
		$('#add_form_hidden').submit();
		return false;
	});

	// update webform action button
	$('#update_webform').click(function() {
		$('#action').attr('value', 'update_webform');
		$('#webform-editor-form').submit();
	});

	// delete webform action button
	$('#delete_webform').click(function() {
		var confirmed = confirm('Are you sure?');
		if (confirmed) {
			$('#action').attr('value', 'delete_webform');
			$('#webform-editor-form').submit();
		}
	});
});

function addFormField(type) {
	$('#action').attr('value', 'add_' + type);
	$('#webform-editor-form').submit();
}

function deleteFormField(itemId, confirmMessage) {
	var confirmed = confirm(confirmMessage);
	if (confirmed) {
		$('#action').attr('value', 'delete_form_item');
		$('#webform_item_to_delete').attr('value', itemId);
		$('#webform-editor-form').submit();
	} else {
		return false;
	}
}

function addFormHandler(type) {
	$('#action').attr('value', 'add_handler_' + type);
	$('#webform-editor-form').submit();
}

function deleteFormHandler(handlerId, confirmMessage) {
	var confirmed = confirm(confirmMessage);
	if (confirmed) {
		$('#action').attr('value', 'delete_form_handler');
		$('#webform_handler_to_delete').attr('value', handlerId);
		$('#webform-editor-form').submit();
	} else {
		return false;
	}
}

function onCaptchaChanged(captchaKeyFieldClass) {
	$('.' + captchaKeyFieldClass).toggleClass('displaynone');
}