<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/validators/form_validator.php";
	include_once FRONTEND_REQUEST . "libraries/handlers/form_handler.php";
	include_once FRONTEND_REQUEST . "libraries/system/notifications.php";
	include_once FRONTEND_REQUEST . "database/dao/block_dao.php";
	
	// =================================== BLOCKS ============================================================
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'delete_block':
					if (isset($_POST['element_holder_id'])) {
						$element_holder_id = $_POST['element_holder_id'];
						deleteBlock($element_holder_id);
					}
					break;
			}
		} else if (isset($_POST['add_block_action'])) {
			addBlock();
		}
	}
	
	// block must be deleted
	function deleteBlock($element_holder_id) {
		$block_dao = BlockDao::getInstance();
		$block = $block_dao->getBlock($element_holder_id);
		$block_dao->deleteBlock($block);
		Notifications::setSuccessMessage("Blok succesvol verwijderd");
		header('Location: /admin/index.php');
		exit();
	} 
	
	// a new block must be created
	function addBlock() {
		$block_dao = BlockDao::getInstance();
		$new_block = $block_dao->createBlock();
		Notifications::setSuccessMessage("Blok succesvol aangemaakt");
		header('Location: /admin/index.php?block=' . $new_block->getId());
		exit();
	}
	
	// ========================= POSITIONS ===================================================================
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'delete_positions':
					break;
				case 'update_position':
					updatePosition();
					break;
			}
		}
		if (isset($_POST['position_delete_action'])) {
			deletePositions();
		}
	}
	
	// position(s) must be deleted
	function deletePositions() {
		$block_dao = BlockDao::getInstance();
		$positions = $block_dao->getBlockPositions();
		foreach ($positions as $position) {
			if (isset($_POST['position_' . $position->getId() . '_delete'])) {
				$block_dao->deleteBlockPosition($position);
			}
		}
		Notifications::setSuccessMessage("Positie(s) succesvol verwijderd");
	}
	
	// position is being updated
	function updatePosition() {
		global $errors;
		$block_dao = BlockDao::getInstance();

		$name = FormValidator::checkEmpty('name', 'Titel is verplicht');
		$explanation = FormHandler::getFieldValue('explanation');
		
		if ($name != '') {
			$existing_position = $block_dao->getBlockPositionByName($name);
			
			if (!is_null($existing_position) && !(isset($_GET['position']) && $_GET['position'] == $existing_position->getId())) {
				$errors['name_error'] = 'Er bestaat al een positie met deze naam';
			}
		}
		
		if (count($errors) == 0) {
			
			if (isset($_POST['position_id']) && $_POST['position_id'] != '') {
				$current_position = $block_dao->getBlockPosition($_POST['position_id']);
				$current_position->setName($name);
				$current_position->setExplanation($explanation);
				$block_dao->updateBlockPosition($current_position);
				Notifications::setSuccessMessage("Positie succesvol aangemaakt");
			} else if (isset($_GET['new_position'])) {
				// create new position
				$new_position = $block_dao->createBlockPosition();
				$new_position->setName($name);
				$new_position->setExplanation($explanation);
				$block_dao->updateBlockPosition($new_position);
				Notifications::setSuccessMessage("Positie succesvol aangemaakt");
				header('Location: /admin/index.php?position=' . $new_position->getId());
				exit();
			}
		} else {
			Notifications::setFailedMessage("Positie niet opgeslagen, verwerk de fouten");
		}
	}
?>