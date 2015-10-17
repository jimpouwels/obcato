$(document).ready(function() {
	$('#update_template').click(function() {
		var $actionField = $('#action');
		if ($actionField.length == 0) {
			alert('Fout: Kan de actie niet bepalen');
		} else {
			$actionField.attr('value', 'update_template');
			var $template_form = $('#template_editor_form');
			if ($template_form.length == 0) {
				alert('Fout: Kan template niet opslaan');
			} else {
				$template_form.submit();
			}
		}
	});
	
	$('#add_template').click(function() {
		var $actionField = $('#action');
		if ($actionField.length == 0) {
			alert('Fout: Kan de actie niet bepalen');
		} else {
			$actionField.attr('value', 'add_template');
			var $template_form = $('#template_add_form');
			if ($template_form.length == 0) {
				alert('Fout: Kan geen nieuw template toevoegen');
			} else {
				$template_form.submit();
			}
		}
	});
	
	$('#delete_template').click(function() {
		var $actionField = $('#action');
		if ($actionField.length == 0) {
			alert('Fout: Kan de actie niet bepalen');
		} else {
			$actionField.attr('value', 'delete_template');
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
					var $template_form = $('#template_delete_form');
					if ($template_form.length == 0) {
						alert('Fout: Kan geselecteerde templates niet verwijderen');
					} else {
						$template_form.submit();
					}
				}
			}
		}
	});
});