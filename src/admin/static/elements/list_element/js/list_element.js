function addListItem(elementId, formId) {
	var $elementAddItemField = $('#element' + elementId + '_add_item');
	if ($elementAddItemField.length == 0) {
		alert('Fout: kan item niet aan element toevoegen');
	} else {
		$elementAddItemField.attr('value', 'TRUE');
		$('#action').attr('value', 'update_element_holder');
		var $formToSubmit = $('#' + formId);
		if ($formToSubmit.length == 0) {
			alert('Fout: kan item niet toevoegen omdat er geen actieve editor is gevonden');
		} else {
			$formToSubmit.submit();
		}
	}
}