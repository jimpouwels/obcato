/*
	Author: Jim Pouwels
	Date: August 16th, 2011
*/
$(document).ready(function() {
	$("#article_list").kendoGrid({
		sortable: true,
		pageable: {
			refresh: true,
			pageSize: 10,
			buttonCount: 5
		},
        columns: [
            { field: 'Title', title: 'Titel' },
            { field: 'CreationDate', title: 'Aangemaakt op' },
            { field: 'CreatedBy', title: 'Aangemaakt door' },
            { field: 'Published', title: 'Gepubliceerd' }]
	});

	$('#add_element_holder').click(function() {
		$('#add_article_action').attr('value', 'add_article');
		$('#add_form_hidden').submit();
	});

	$('#update_element_holder').click(function() {
		$('#action').attr('value', 'update_element_holder');
		$('#element_holder_form_id').submit();
	});

	$('#delete_element_holder').click(function() {
		var confirmed = confirm("Weet u zeker dat u dit artikel wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_article');
			$('#element_holder_form_id').submit();
		} else {
			return false;
		}
	});

	$('#add_term').click(function() {
		$('#add_term_action').attr('value', 'add_term');
		$('#add_term_form_hidden').submit();
	});

	$('#update_term').click(function() {
		$('#action').attr('value', 'update_term');
		$('#term_form').submit();
	});

	$('#delete_terms').click(function() {
		var $checked = false;
		$('input:checkbox').each(function() {
			if ($(this).attr('checked')) {
				$checked = true;
			}
		});
		if (!$checked) {
			alert('U heeft geen termen geselecteerd');
		} else {
			var confirmed = confirm('Weet u zeker dat u de geselecteerde termen wilt verwijderen?');
			if (confirmed) {
				$('#term_delete_action').attr('value', 'delete_terms');
				$('#term_delete_form').submit();
			}
		}
	});

	$('#delete_lead_image').click(function() {
		var confirmed = confirm("Weet u zeker dat u deze afbeelding wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'update_element_holder');
			$('#delete_lead_image_field').attr('value', 'true');
			$('#element_holder_form_id').submit();
		}
		return false;
	});

	$('#update_target_pages').click(function() {
		$('#update_target_page_form').submit();
	});

	$('#delete_target_pages').click(function() {
		var $checked = false;
		$('input:checkbox').each(function() {
			if ($(this).attr('checked')) {
				$checked = true;
			}
		});
		if (!$checked) {
			alert('U heeft geen doelpagina\'s geselecteerd');
		} else {
			var confirmed = confirm('Weet u zeker dat u de geselecteerde doelpagina\'s wilt verwijderen?');
			if (confirmed) {
				$('#action').attr('value', 'delete_target_pages');
				$('#update_target_page_form').submit();
			}
		}
	});
});

function changeDefaultTargetPage(pageId) {
	$('#action').attr('value', 'change_default_target_page');
	$('#new_default_target_page').attr('value', pageId);
	$('#update_target_page_form').submit();
}