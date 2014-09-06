<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "/pre_handlers/pre_handler.php";
	
	class LinkPreHandler extends PreHandler {
	
		public function handle() {
			// Adds a link to the current element holder
			if (isset($_POST[ACTION_FORM_ID]) && $_POST[ACTION_FORM_ID] == 'add_link' && isset($_POST[EDIT_ELEMENT_HOLDER_ID])) {
				include_once CMS_ROOT . "/database/dao/link_dao.php";
			
				// first obtain the element holder
				$element_holder_id = $_POST[EDIT_ELEMENT_HOLDER_ID];
				
				$link_dao = LinkDao::getInstance();
				
				$link_dao->createLink($element_holder_id);
			}
			
			// Updates all links in the element holder
			if (isset($_POST[ACTION_FORM_ID]) && $_POST[ACTION_FORM_ID] == 'update_element_holder' && isset($_POST[EDIT_ELEMENT_HOLDER_ID])) {
				include_once CMS_ROOT . "/database/dao/link_dao.php";
				include_once CMS_ROOT . "/libraries/handlers/form_handler.php";
				
				$link_dao = LinkDao::getInstance();
				$links = $link_dao->getLinksForElementHolder($_POST[EDIT_ELEMENT_HOLDER_ID]);
				
				foreach ($links as $link) {
					if (isset($_POST['link_' . $link->getId() . '_delete'])) {
						$link_dao->deleteLink($link);
					} else {
						if (isset($_POST['link_' . $link->getId() . '_title'])) {
							$link->setTitle(FormHandler::getFieldValue('link_' . $link->getId() . '_title'));
						}
						if (isset($_POST['link_' . $link->getId() . '_url'])) {
							$link->setTargetAddress(FormHandler::getFieldValue('link_' . $link->getId() . '_url'));
						}
						if (isset($_POST['link_' . $link->getId() . '_code'])) {
							$link->setCode(FormHandler::getFieldValue('link_' . $link->getId() . '_code'));
						}
						if (isset($_POST['link_element_holder_ref_' . $link->getId()])) {
							$link->setTargetElementHolderId(FormHandler::getFieldValue('link_element_holder_ref_' . $link->getId()));
						}
						if (isset($_POST['delete_link_target']) && ($_POST['delete_link_target'] == $link->getId())) {
							$link->setTargetElementHolderId(NULL);
						}
					}
					$link_dao->updateLink($link);
				}
			}
		}
		
	}

?>