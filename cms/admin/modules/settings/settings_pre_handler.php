<?php
	// No direct access
	defined("_ACCESS") or die;
	
	require_once "database/dao/settings_dao.php";
	require_once "view/request_handlers/module_request_handler.php";
	require_once "libraries/validators/form_validator.php";
	require_once "libraries/system/notifications.php";
	require_once "modules/settings/settings_form.php";

	class SettingsPreHandler extends ModuleRequestHandler {
	
		private $_settings_dao;
		
		public function __construct() {
			$this->_settings_dao = SettingsDao::getInstance();
		}
	
		public function handleGet() {
		}
		
		public function handlePost() {
			$settings = $this->_settings_dao->getSettings();
			$settings_form = new SettingsForm($settings);
			try {
				$settings_form->loadFields();
				$this->_settings_dao->update($settings);
				$this->_settings_dao->setHomepage($settings_form->getHomepageId());
				Notifications::setSuccessMessage("Instellingen succesvol opgeslagen");
			} catch (FormException $e) {
				global $errors;
				var_dump($errors);
				Notifications::setFailedMessage("Instellingen niet opgeslagen, verwerk de fouten");
			}
		}
	}
?>