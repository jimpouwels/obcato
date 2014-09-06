<?php

	// No direct access
	defined('_ACCESS') or die;
	
	if (isset($_POST['action']) && $_POST['action'] == 'update_element_holder') {
		include_once CMS_ROOT . "/libraries/handlers/form_handler.php";
	
		$element->setGuestBookId(FormHandler::getFieldValue('element_' . $element->getId() . '_guestbook'));
		$element->setTemplateId(FormHandler::getFieldValue('element_' . $element->getId() . '_template'));
		$element_dao = ElementDao::getInstance();
		$element_dao->updateElement($element);
	}
?>