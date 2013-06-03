<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/renderers/form_renderer.php";
	include_once "libraries/handlers/form_handler.php";
	
	if (isset($_POST['action']) && $_POST['action'] == 'update_element_holder') {
		$element->setTitle(FormHandler::getFieldValue('element_' . $element->getId() . '_title'));
		$element->setText(FormHandler::getFieldValue('element_' . $element->getId() . '_text'));
		$element->setTemplateId(FormHandler::getFieldValue('element_' . $element->getId() . '_template'));
		$element_dao = ElementDao::getInstance();
		$element_dao->updateElement($element);
	}
?>