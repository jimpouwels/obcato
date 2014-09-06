<?php
	// No direct access
	defined('_ACCESS') or die;
		
	include_once CMS_ROOT . "/libraries/validators/form_validator.php";
	include_once CMS_ROOT . "/libraries/handlers/form_handler.php";
	include_once CMS_ROOT . "/libraries/system/notifications.php";
	include_once CMS_ROOT . "/database/dao/guestbook_dao.php";
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'delete_guestbook':
					deleteGuestBook();
					break;
				case 'update_guestbook':
					updateGuestBook();
					break;
				case 'add_guestbook':
					addGuestBook();
					break;
			}
		}
	}
	
	/*
		Adds a new guestbook.
	*/
	function addGuestBook() {
		$guestbook_dao = GuestBookDao::getInstance();
		$new_guestbook = $guestbook_dao->createGuestBook();
		
		Notifications::setSuccessMessage("Gastenboek succesvol aangemaakt");
		header('Location: /admin/index.php?guestbook=' . $new_guestbook->getId());
		exit();
	}
	
	/*
		Deletes the selected guestbook.
	*/
	function deleteGuestBook() {
		$guestbook_dao = GuestBookDao::getInstance();
		$new_guestbook = $guestbook_dao->deleteGuestBook($_GET['guestbook']);
		
		Notifications::setSuccessMessage("Gastenboek succesvol verwijderd");
	}
	
	/*
		Updates the selected guestbook.
	*/
	function updateGuestBook() {
		global $errors;
		
		$title = FormValidator::checkEmpty('guestbook_title', 'Titel is verplicht');
		$closed = FormHandler::getFieldValue('guestbook_closed');
		$auto_acknowledge = FormHandler::getFieldValue('guestbook_auto_acknowledge');
		
		if (count($errors) == 0) {
			$guestbook_dao = GuestBookDao::getInstance();
			$guestbook = $guestbook_dao->getGuestBook($_GET['guestbook']);
			
			$closed_value = 0;
			if ($closed == 'on') {
				$closed_value = 1;
			}
			$auto_acknowledge_value = 0;
			if ($auto_acknowledge == 'on') {
				$auto_acknowledge_value = 1;
			}
			
			$guestbook->setTitle($title);
			$guestbook->setClosed($closed_value);
			$guestbook->setAutoAcknowledge($auto_acknowledge_value);
			
			$guestbook_dao->updateGuestBook($guestbook);
			
			// now delete the messages if needed
			foreach ($guestbook->getMessages() as $message) {
				$field_to_find = 'guestbook_message_' . $message->getId() . '_delete';
				if (isset($_POST[$field_to_find]) && $_POST[$field_to_find] != '') {
					$guestbook_dao->deleteMessage($guestbook_dao->getMessage($message->getId())->getId());
				}				
			}
		
			Notifications::setSuccessMessage("Gastenboek succesvol opgeslagen");
		} else {
			Notifications::setFailedMessage("Gastenboek niet opgeslagen, verwerk de fouten");
		}
	}
?>