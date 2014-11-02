<?php

	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "request_handlers/form.php";
	
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
			$this->_settings->setRootDir($this->preserveBackSlashes($this->getMandatoryFieldValue("root_dir", "Root directory is verplicht")));
			$this->_settings->setFrontendTemplateDir($this->preserveBackSlashes($this->getMandatoryFieldValue("frontend_template_dir", "Template directory is verplicht")));
			$this->_settings->setSmtpHost($this->getFieldValue("smtp_host"));
			$this->_settings->setEmailAddress($this->getEmailAddress("email_address", "Vul een geldig email adres in"));
			$this->_settings->setStaticDir($this->preserveBackSlashes($this->getMandatoryFieldValue("static_dir", "Static directory is verplicht")));
			$this->_settings->setConfigDir($this->preserveBackSlashes($this->getMandatoryFieldValue("config_dir", "Configuration directory is verplicht")));
			$this->_settings->setUploadDir($this->preserveBackSlashes($this->getMandatoryFieldValue("upload_dir", "Upload directory is verplicht")));
			$this->_settings->setComponentDir($this->preserveBackSlashes($this->getMandatoryFieldValue("component_dir", "Component directory is verplicht")));
			$this->_settings->setBackendTemplateDir($this->preserveBackSlashes($this->getMandatoryFieldValue("backend_template_dir", "Template Engine directory is verplicht")));
			$this->_homepage_id = $this->getMandatoryFieldValue("homepage_page_id", "De website heeft een homepage nodig");
		
			if ($this->hasErrors())
				throw new FormException();
		}
		
		public function getHomepageId() {
			return $this->_homepage_id;
		}

        private function preserveBackSlashes($value) {
            return str_replace("\\", "\\\\", $value);
        }
	
	}
	