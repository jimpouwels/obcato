/*
	Author: Jim Pouwels
	Date: August 16th, 2011
*/

// initialize event handlers
$(document).ready(function() {

	// add article button
	$('#add_element_holder').click(function() {
		$('#add_article_action').attr('value', 'add_article');
		$('#add_form_hidden').submit();
	});
	
	// update article button
	$('#update_element_holder').click(function() {
		$('#action').attr('value', 'update_element_holder');
		$('#element_holder_form_id').submit();
	});
	
	// delete article button
	$('#delete_element_holder').click(function() {
		var confirmed = confirm("Weet u zeker dat u dit artikel wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'delete_article');
			$('#element_holder_form_id').submit();
		} else {
			return false;
		}
	});
	
	// add term
	$('#add_term').click(function() {
		$('#add_term_action').attr('value', 'add_term');
		$('#add_term_form_hidden').submit();
	});
	
	// update term button
	$('#update_term').click(function() {
		$('#action').attr('value', 'update_term');
		$('#term_form').submit();
	});
	
	// delete terms button
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
	
	// delete leade image
	$('#delete_lead_image').click(function() {
		var confirmed = confirm("Weet u zeker dat u deze afbeelding wilt verwijderen?");
		if (confirmed) {
			$('#action').attr('value', 'update_element_holder');
			$('#delete_lead_image_field').attr('value', 'true');
			$('#element_holder_form_id').submit();
		}
		return false;
	});
	
	// update options
	$('#update_target_pages').click(function() {
		$('#update_target_page_form').submit();
	});
	
	// delete target pages button
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
	
// article options make default target page
function changeDefaultTargetPage(pageId) {
	$('#action').attr('value', 'change_default_target_page');
	$('#new_default_target_page').attr('value', pageId);
	$('#update_target_page_form').submit();
}