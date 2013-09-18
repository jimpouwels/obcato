<?php

	// No direct access
	defined('_ACCESS') or die;
	
	if (isset($_POST['action']) && $_POST['action'] == 'update_element_holder') {
		include_once FRONTEND_REQUEST . "libraries/handlers/form_handler.php";
		
		$element->setTitle(FormHandler::getFieldValue('element_' . $element->getId() . '_title'));
		$element->setTemplateId(FormHandler::getFieldValue('element_' . $element->getId() . '_template'));
		// update list items
		foreach ($element->getListItems() as $list_item) {
			if (isset($_POST['listitem_' . $list_item->getId() . '_delete'])
				&& $_POST['listitem_' . $list_item->getId() . '_delete'] != '') {
				$element->deleteListItem($list_item);
			} else {
				$list_item->setText(FormHandler::getFieldValue('listitem_' . $list_item->getId() . '_text'));
			}
		}
		
		if (!is_null($element) && isset($_POST['element' . $element->getId() . '_add_item']) && 
			$_POST['element' . $element->getId() . '_add_item'] != '') {
			$element->addListItem();
		}
		
		$element_dao = ElementDao::getInstance();
		$element_dao->updateElement($element);
	}
?>