<?php

	defined("_ACCESS") or die;
	
	require_once "pre_handlers/form.php";
	
	class SettingsForm extends Form {
	
		private $_settings;
		private $_homepage_id;
	
		public function __construct($settings) {
			$this->_settings = $settings;
		}
	
		public function loadFields() {
			$this->_settings->setWebsiteTitle($this->getMandatoryFieldValue("website_title", "Titel is verplicht"));
			$this->_settings->setFrontendHostname($this->getMandatoryFieldValue("frontend_hostname", "Frontend hostname is verplicht"));
			$this->_settings->setBackendHostname($this->getMandatoryFieldValue("backend_hostname", "Backend hostname is verplicht"));
			$this->_settings->setRootDir($this->getMandatoryFieldValue("root_dir", "Root directory is verplicht"));
			$this->_settings->setFrontendTemplateDir($this->getMandatoryFieldValue("frontend_template_dir", "Template directory is verplicht"));
			$this->_settings->setSmtpHost($this->getFieldValue("smtp_host"));
			$this->_settings->setEmailAddress($this->getEmailAddress("email_address"));
			$this->_settings->setStaticDir($this->getMandatoryFieldValue("static_dir", "Static directory is verplicht"));
			$this->_settings->setConfigDir($this->getMandatoryFieldValue("config_dir", "Configuration directory is verplicht"));
			$this->_settings->setUploadDir($this->getMandatoryFieldValue("upload_dir", "Upload directory is verplicht"));
			$this->_settings->setComponentDir($this->getMandatoryFieldValue("component_dir", "Component directory is verplicht"));
			$this->_settings->setBackendTemplateDir($this->getMandatoryFieldValue("backend_template_dir", "Template Engine directory is verplicht"));
			$this->_homepage_id = $this->getMandatoryFieldValue("homepage_page_id", "De website heeft een homepage nodig");
		
			if ($this->hasErrors())
				throw new FormException();
		}
		
		public function getHomepageId() {
			return $this->_homepage_id;
		}
	
	}
	