<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/handlers/form_handler.php";
	
	if (isset($_POST['action']) && $_POST['action'] == 'update_element_holder') {
		if (isset($_POST[DELETE_ELEMENT_FORM_ID]) && $_POST[DELETE_ELEMENT_FORM_ID] == $element->getId()) {
			$element_dao = ElementDao::getInstance();
			$element_dao->deleteElement($element);
			$element = NULL;
		} else {
			include_once "libraries/utilities/string_utility.php";
			$element->setTitle(FormHandler::getFieldValue('element_' . $element->getId() . '_title'));
			$element->setEmbed(FormHandler::getFieldValue('element_' . $element->getId() . '_embed'));
			$element->setTemplateId(FormHandler::getFieldValue('element_' . $element->getId() . '_template'));
			$element_dao = ElementDao::getInstance();
			$element_dao->updateElement($element);
		}
	}
?>