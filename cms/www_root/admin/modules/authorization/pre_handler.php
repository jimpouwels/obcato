<?php
	// No direct access
	defined('_ACCESS') or die;
		
	include_once FRONTEND_REQUEST . "libraries/validators/form_validator.php";
	include_once FRONTEND_REQUEST . "libraries/handlers/form_handler.php";
	include_once FRONTEND_REQUEST . "libraries/system/notifications.php";
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action']) && isset($_POST['user_id'])) {
			$user_id = $_POST['user_id'];
			switch ($_POST['action']) {
				case 'delete_user':
					deleteUser($user_id);
					break;
				case 'add_user':
					addUser();
					break;
				case 'update_user':
					updateUser($user_id);
					break;
			}
		}
	}	

	// user must be deleted
	function deleteUser($user_id) {
		$authorization_dao = AuthorizationDao::getInstance();
		$authorization_dao->deleteUser($user_id);
		Notifications::setSuccessMessage("Gebruiker succesvol verwijderd");
		header('Location: /admin/index.php');
		exit();
	} 
	
	// a new user must be created
	function addUser() {
		include_once FRONTEND_REQUEST . "libraries/utilities/password_utility.php";
	
		$authorization_dao = AuthorizationDao::getInstance();
		$new_user = $authorization_dao->createUser();
		$password = PasswordUtility::generatePassword();
		$new_user->setUuid(uniqid());
		$new_user->setPassword($password);
		$authorization_dao->updateUser($new_user);
		Notifications::setSuccessMessage("Gebruiker aangemaakt, met wachtwoord: " . $password);
		header('Location: /admin/index.php?user=' . $new_user->getId());
		exit();
	}
	
	// page is being updated
	function updateUser($user_id) {
		global $errors;
		$authorization_dao = AuthorizationDao::getInstance();
		$current_user = $authorization_dao->getUser($user_id);
		
		$username = FormValidator::checkEmpty('user_username', 'Gebruikersnaam is verplicht');
		$check_user = $authorization_dao->getUser($username);
		if (!is_null($check_user) && ($check_user->getId() != $user_id)) {
			$errors['user_username_error'] = "Er bestaat al een gebruiker met deze gebruikersnaam";;
		}
		
		$first_name = FormValidator::checkEmpty('user_firstname', 'Voornaam is verplicht');
		$last_name = FormValidator::checkEmpty('user_lastname', 'Voornaam is verplicht');
		$prefix = FormHandler::getFieldValue('user_prefix');
		$email = FormValidator::checkEmpty('user_email', 'Email adres is verplicht');
		
		$password1 = FormHandler::getFieldValue('user_new_password_first');
		$password2 = FormHandler::getFieldValue('user_new_password_second');
		$password_value = '';
		if (!is_null($password1) && $password1 != '' || !is_null($password2) && $password2 != '') {
			$password_value = FormValidator::checkPassword('user_new_password_first', 'user_new_password_second');
		}
		
		if (count($errors) == 0) {
			$current_user = $authorization_dao->getUserById($user_id);
			$current_user->setUsername($username);
			$current_user->setFirstName($first_name);
			$current_user->setLastName($last_name);
			$current_user->setPrefix($prefix);
			$current_user->setEmailAddress($email);
			if ($password_value != '') {
				$current_user->setPassword($password_value);
			}
			$authorization_dao->updateUser($current_user);
			
			Notifications::setSuccessMessage("Gebruiker succesvol opgeslagen");
		} else {
			Notifications::setFailedMessage("Gebruiker niet opgeslagen, verwerk de fouten");
		}
	}
?>