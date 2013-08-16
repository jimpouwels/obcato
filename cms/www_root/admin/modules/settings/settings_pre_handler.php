<?php
	// No direct access
	defined("_ACCESS") or die;
	
	require_once "database/dao/settings_dao.php";
	require_once "view/request_handlers/module_request_handler.php";
	require_once "libraries/renderers/form_renderer.php";
	require_once "libraries/validators/form_validator.php";
	require_once "libraries/system/notifications.php";

	class SettingsPreHandler extends ModuleRequestHandler {
	
		public function handleGet() {
		}
		
		public function handlePost() {
			$website_title = FormValidator::checkEmpty("website_title", "Website titel is verplicht");
			$frontend_hostname = FormValidator::checkEmpty("frontend_hostname", "Frontend hostname is verplicht");
			$backend_hostname = FormValidator::checkEmpty("backend_hostname", "Backend hostname is verplicht");
			$root_dir = FormValidator::checkEmpty("root_dir", "Root directory is verplicht");
			$frontend_template_dir = FormValidator::checkEmpty("frontend_template_dir", "Template directory is verplicht");
			$smtp_host = FormHandler::getFieldValue("smtp_host");
			$email_address = FormValidator::checkEmailAddress("email_address", true, "Ongeldig email adres");
			$static_dir = FormValidator::checkEmpty("static_dir", "Static directory is verplicht");
			$config_dir = FormValidator::checkEmpty("config_dir", "Configuration directory is verplicht");
			$upload_dir = FormValidator::checkEmpty("upload_dir", "Upload directory is verplicht");
			$component_dir = FormValidator::checkEmpty("component_dir", "Component directory is verplicht");
			$backend_template_dir = FormValidator::checkEmpty("backend_template_dir", "Template Engine directory is verplicht");
			$homepage_id = FormValidator::checkEmpty("homepage_page_id", "De website heeft een homepage nodig");
		
			global $errors;
			if (count($errors) == 0) {
				$settings = Settings::find();
				$settings->setWebsiteTitle($website_title);
				$settings->setBackendHostname($backend_hostname);
				$settings->setFrontendHostname($frontend_hostname);
				$settings->setEmailAddress($email_address);
				$settings->setSmtpHost($smtp_host);
				$settings->setRootDir($root_dir);
				$settings->setFrontendTemplateDir($frontend_template_dir);
				$settings->setStaticDir($static_dir);
				$settings->setConfigDir($config_dir);
				$settings->setUploadDir($upload_dir);
				$settings->setComponentDir($component_dir);
				$settings->setBackendTemplateDir($backend_template_dir);
				$settings->update();
				
				include_once "database/dao/settings_dao.php";
				$settings_dao = SettingsDao::getInstance();
				$settings_dao->setHomepage($homepage_id);
				
				Notifications::setSuccessMessage("Instellingen succesvol opgeslagen");
			} else {
				Notifications::setFailedMessage("Instellingen niet opgeslagen, verwerk de fouten");
			}
		}
	}
?>