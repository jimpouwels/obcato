<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "libraries/system/notifications.php";
	include_once CMS_ROOT . "database/dao/element_dao.php";
	include_once CMS_ROOT . "database/dao/article_dao.php";
	include_once CMS_ROOT . "libraries/utilities/date_utility.php";
	include_once CMS_ROOT . "libraries/handlers/form_handler.php";
	include_once CMS_ROOT . "libraries/validators/form_validator.php";
	
	if (isset($_POST['action']) && $_POST['action'] == 'update_element_holder') {
		$show_until_today = FormHandler::getFieldValue('element_' . $element->getId() . '_show_until_today');
		$show_to = NULL;
		if ($show_until_today == 'on') {
			$show_until_today = 1;
			if ($element->getShowTo() != '') {
				$show_to = DateUtility::mysqlDateToString($element->getShowTo(), '-');
			}
		} else {
			$show_until_today = 0;
			$show_to = FormValidator::checkDate('element_' . $element->getId() . '_show_to', true, 'Vul een datum in (bijv. 31-12-2010)');
		}
		$show_from = FormValidator::checkDate('element_' . $element->getId() . '_show_from', false, 'Vul een datum in (bijv. 31-12-2010)');
		if (!is_null($show_from) && $show_from != '') {
			$show_from = DateUtility::stringMySqlDate($show_from);
		} else {
			$show_from = NULL;
		}
		if (!is_null($show_to) && $show_to != '') {
			$show_to = DateUtility::stringMySqlDate($show_to);
		}
		$number_of_results = FormValidator::checkNumber('element_' . $element->getId() . '_number_of_results', true, 'Vul een geldig getal in');
		$order_by = FormHandler::getFieldValue('element_' . $element->getId() . '_order_by');
		$selected_terms = FormHandler::getFieldValue('select_terms_' . $element->getId());
		
		global $errors;
		if (count($errors) == 0) {
			$element->setTitle(FormHandler::getFieldValue('element_' . $element->getId() . '_title'));
			$element->setTemplateId(FormHandler::getFieldValue('element_' . $element->getId() . '_template'));
			$element->setShowFrom($show_from);
			$element->setShowTo($show_to);
			$element->setShowUntilToday($show_until_today);
			$element->setOrderBy($order_by);
			$element->setNumberOfResults($number_of_results);
			
			if (!is_null($selected_terms) && count($selected_terms) > 0) {
				$article_dao = ArticleDao::getInstance();
				$existing_terms = $element->getTerms();
				foreach ($selected_terms as $selected_term_id) {
					// make sure the term is not added twice
					if (is_null($existing_terms) || count($existing_terms) == 0 || !in_array($article_dao->getTerm($selected_term_id), $existing_terms)) {
						$element->addTerm($selected_term_id);
					}
				}
			}
			
			$element_terms = $element->getTerms();
			foreach ($element_terms as $element_term) {
				if (isset($_POST['term_' . $element->getId() . '_' . $element_term->getId() . '_delete'])) {
					$element->removeTerm($element_term->getId());
				}
			}
				
			$element_dao = ElementDao::getInstance();
			$element_dao->updateElement($element);
		} else {
			Notifications::setFailedMessage("Niet opgeslagen, verwerk de fouten");
		}
	}
?>