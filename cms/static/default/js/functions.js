// handles the 'add element' click in the navigation menu
function addElement(elementTypeId) {
	var $inputField = $('#add_element_type_id');
	if ($inputField.length == 0) {
		alert('Fout: Kan element niet toevoegen. U kunt elementen plaatsen op pagina\'s, artikelen en blokken.');
		return false;
	} else {
		$inputField.attr('value', elementTypeId);
	}
	var $editorForm = $('#element_holder_form_id');
	$editorForm.submit();
}

// Saves the selected element holder back to the link in the parent window
function submitSelectionBackToOpener(backRef, backValue, backClickId) {
	var $backField = window.opener.$('#' + backRef);
	if ($backField.length > 0) {
		$backField.attr('value', backValue);
		window.opener.$('#' + backClickId).click();
		window.close();
	} else {
		alert('Fout: Kan niet opgeslagen worden, waarschijnlijk is het hoofdscherm gesloten');
	}
}

// handles the 'delete element' click
function deleteElement(elementId, formFieldId) {
	var $inputField = $('#' + formFieldId);
	if ($inputField.length == 0) {
		alert('Fout: Kan element niet verwijderen');
		return false;
	} else {
		$inputField.attr('value', elementId);
	}
	var confirmed = confirm("Weet u zeker dat u dit element wilt verwijderen?");
	if (confirmed) {
		$('#action').attr('value', 'update_element_holder');
		$('#element_holder_form_id').submit();
	} else {
		return false;
	}
}

// initializes sortable elements
$(document).ready(function(){
	$(function() {
		$("#sortable").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var idString = '';
			$('.element_id_holder').each(function() {
				if (idString != '') {
					idString += ',';
				}
				idString = idString + $(this).text();
			});
			var $order_field = $('#element_order');
			if ($order_field.length > 0) {
				$order_field.attr("value", idString);
			}
		}
		});
	});
});

// starts sliding in the notification bar
$(document).ready(function(){
	// slides in the notification bar
	$("#notification-slider").animate({
		marginTop: "0"
	}, 1000 );
});

// handles add link
function addLink() {
	var $elementHolderForm = $('#element_holder_form_id');
	if ($elementHolderForm.length == 0) {
		alert('Fout: Kan link niet toevoegen');
		return false;
	} else {
		var $actionField = $('#action');
		$actionField.attr('value', 'add_link');
		$elementHolderForm.submit();		
	}
}

// makes sure every field that should be able to
// contain a link is prepared
var lastFocussedField = undefined;
$(document).ready(function() {
	$('.linkable').each(function() {
		$(this).focus(function() {
			lastFocussedField = document.getElementById($(this).attr("id"));
		});
	});
});

// handles put link
function putLink(linkCode) {
	var errorMessage = "Fout: U heeft geen tekst geselecteerd, of het is niet toegestaan aan het huidige veld een link toe te voegen.";
	if (lastFocussedField == undefined) {
		alert(errorMessage);
	} else {
		var len = lastFocussedField.value.length;
		var start = lastFocussedField.selectionStart;
		var end = lastFocussedField.selectionEnd;
		var value = lastFocussedField.value;
		var selectedText = value.substring(start, end);
		if (selectedText != undefined && selectedText.length > 0) {
			newText = "[LINK C=\"" + linkCode + "\"]" + selectedText + "[/LINK]";
			var result = value.replace(selectedText, newText);
			lastFocussedField.value = lastFocussedField.value.substring(0,start) + newText + lastFocussedField.value.substring(end,len);
			alert("Link succesvol toegevoegd");
		} else {
			alert(errorMessage);
		}
	}
}

// deletes the selected link target for a link
function deleteLink(linkId) {
	var confirmed = confirm("Weet u zeker dat u dit linkdoel wilt verwijderen?");
	if (confirmed) {
		$('#delete_link_target').attr('value', linkId);
		$('#action').attr('value', 'update_element_holder');
		var $editorForm = $('#element_holder_form_id');
		$editorForm.submit();
	}
	return false;
}

// horizontal dropdown menu
$(document).ready(function() {
	$('.module-group').mouseover(function() {
		var $submenu = $(this).find('.submenu');
		$submenu.show();	
	});
	$('#menu > li').mouseout(function() {
		var $submenu = $(this).find('.submenu');
		$submenu.hide();
	});
});