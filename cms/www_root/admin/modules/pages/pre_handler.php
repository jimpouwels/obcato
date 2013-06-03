<?php
	// No direct access
	defined('_ACCESS') or die;
		
	include_once "libraries/validators/form_validator.php";
	include_once "libraries/handlers/form_handler.php";
	include_once "libraries/system/notifications.php";
	include_once "dao/block_dao.php";
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action']) && isset($_POST['element_holder_id'])) {
			$element_holder_id = $_POST['element_holder_id'];
			switch ($_POST['action']) {
				case 'delete_page':
					deletePage($element_holder_id);
					break;
				case 'sub_page':
					addSubPage($element_holder_id);
					break;
				case 'update_element_holder':
					updatePage($element_holder_id);
					break;
				case 'move_up':
					moveUp($element_holder_id);
					break;
				case 'move_down':
					moveDown($element_holder_id);
					break;
			}
		}
	}	

	// page must be deleted
	function deletePage($element_holder_id) {
		$page_to_delete = Page::findById($element_holder_id);
		$parent = $page_to_delete->getParent();
		$page_to_delete->delete();
		$current_level_pages = $parent->getSubPages();
		updateFollowUp($current_level_pages);
		Notifications::setSuccessMessage("Pagina succesvol verwijderd");
		header('Location: /admin/index.php?page=1');
		exit();
	} 
	
	// a new page must be created
	function addSubPage($parent_element_holder_id) {
		$new_page = new Page();
		$new_page->setParentId($parent_element_holder_id);
		$new_page->persist();
		
		$parent = Page::findById($parent_element_holder_id);
		$current_level_pages = $parent->getSubPages();
		$current_level_pages[] = $new_page;
		updateFollowUp($current_level_pages);
		
		Notifications::setSuccessMessage("Pagina succesvol aangemaakt");
		header('Location: /admin/index.php?page=' . $new_page->getId());
		exit();
	}
	
	// page is being updated
	function updatePage($element_holder_id) {
		global $errors;
		$block_dao = BlockDao::getInstance();
		$element_dao = ElementDao::getInstance();
		$current_page = Page::findById($element_holder_id);
		$page_title = FormValidator::checkEmpty('page_title', 'Titel is verplicht');
		$description = FormHandler::getFieldValue('description');
		$navigation_title = FormValidator::checkEmpty('navigation_title', 'Navigatietitel is verplicht');
		$show_in_navigation = FormHandler::getFieldValue('show_in_navigation');
		$published = FormHandler::getFieldValue('published');
		$template_id = FormHandler::getFieldValue('page_template');
		$element_order = FormHandler::getFieldValue('element_order');
		$selected_blocks = FormHandler::getFieldValue('select_blocks_' . $current_page->getId());
	
		if (count($errors) == 0) {
			$element_dao->updateElementOrder($element_order, $current_page);
			
			$current_page->setTitle($page_title);
			$current_page->setNavigationTitle($navigation_title);
			$current_page->setTemplateId($template_id);
			$current_page->setDescription($description);
			
			$show_in_navigation_value = 0;
			if ($show_in_navigation == 'on') {
				$show_in_navigation_value = 1;
			}
			$current_page->setShowInNavigation($show_in_navigation_value);
			$published_value = 0;
			if ($published == 'on') {
				$published_value = 1;
			}			
			$current_page->setPublished($published_value);
			
			$block_references = $current_page->getBlocks();
			if (!is_null($selected_blocks) && count($selected_blocks) > 0) {
				foreach ($selected_blocks as $selected_block_id) {
					$selected_block = $block_dao->getBlock($selected_block_id);
					// make sure the block is not added twic
					if ($selected_block_id != -1 && (is_null($block_references) || count($block_references) == 0) || !in_array($selected_block, $block_references)) {
						$current_page->addBlock($selected_block);
					}
				}
			}
						
			foreach ($block_references as $block_reference) {
				if (isset($_POST['block_' . $current_page->getId() . "_" . $block_reference->getId() . '_delete'])) {
					$current_page->deleteBlock($block_reference);
				}
			}
			
			$current_page->update();
			
			Notifications::setSuccessMessage("Pagina succesvol opgeslagen");
		} else {
			Notifications::setFailedMessage("Pagina niet opgeslagen, verwerk de fouten");
		}
	}
	
	// move up
	function moveUp($element_holder_id) {
		$page_to_move = Page::findById($element_holder_id);
		$page_to_move->moveUp();
	}
	
	// move down
	function moveDown($element_holder_id) {
		$page_to_move = Page::findById($element_holder_id);
		$page_to_move->moveDown();
	}
	
	// updates the follow up
	function updateFollowUp($pages) {
		for ($i = 0; $i < count($pages); $i++) {
			$pages[$i]->setFollowUp($i);
			$pages[$i]->update();
		}
	}
?>