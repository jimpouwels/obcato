$(document).ready(function() {
	$('.show_until_today_checkbox').each(function() {
		$fromDateField = $(this);
		if ($fromDateField.attr('checked')) {
			toggleDisabled($(this));
		}
		$(this).click(function() {
			toggleDisabled($(this));
		});
	});
});

function toggleDisabled($checkBoxField) {
	$id = $checkBoxField.attr('id');
	$id = $id.replace('_show_until_today', '');
	$id = $id.replace('element_', '');
	$fromDateField = $('#element_' + $id + '_show_to');
	if ($fromDateField.attr('disabled') == 'disabled') {
		$fromDateField.removeAttr('disabled');
	} else {
		$fromDateField.attr('disabled', 'disabled');
	}
}