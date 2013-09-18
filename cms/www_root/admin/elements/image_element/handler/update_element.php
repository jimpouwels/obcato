<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/handlers/form_handler.php";
	
	if (isset($_POST['action']) && $_POST['action'] == 'update_element_holder') {
		include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
		$element->setTitle(FormHandler::getFieldValue('element_' . $element->getId() . '_title'));
		$element->setAlternativeText(FormHandler::getFieldValue('element_' . $element->getId() . '_alternative_text'));
		$element->setAlign(FormHandler::getFieldValue('element_' . $element->getId() . '_align'));
		$element->setTemplateId(FormHandler::getFieldValue('element_' . $element->getId() . '_template'));
		$element->setImageId(FormHandler::getFieldValue('image_image_ref_' . $element->getId()));
		$element_dao = ElementDao::getInstance();
		$element_dao->updateElement($element);
	}
?>