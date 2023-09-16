$(document).ready(function() {
	function submitTemplateForm(action) {
		var $actionField = $('#action');
		if ($actionField.length == 0) {
			alert('Fout: Kan de actie niet bepalen');
		} else {
			$actionField.attr('value', action);
			var $template_form = $('#template_form');
			if ($template_form.length == 0) {
				alert('Fout: Kan template niet opslaan');
			} else {
				$template_form.submit();
			}
		}
	}

	$('#update_template').click(function() {
		submitTemplateForm('update_template');
	});
	
	$('#reload_template').click(function() {
		submitTemplateForm('reload_template');
	});
	
	$('#add_template').click(function() {
		submitTemplateForm('add_template');
	});
	
	$('#delete_template').click(function() {
		var $checked = false;
		$('input:checkbox').each(function() {
			if ($(this).attr('checked')) {
				$checked = true;
			}
		});
		if (!$checked) {
			alert('U heeft geen templates geselecteerd');
		} else {
			var confirmed = confirm('Weet u zeker dat u de geselecteerde templates wilt verwijderen?');
			if (confirmed) {
				submitTemplateForm('delete_templates');
			}
		}
	});
});