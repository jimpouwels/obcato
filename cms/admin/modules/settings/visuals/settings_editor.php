<?php

	// No direct access
	defined("_ACCESS") or die;

	require_once FRONTEND_REQUEST . "view/views/page_picker.php";
	
	class SettingsEditor extends Visual {
	
		private static $TEMPLATE = "modules/settings/editor.tpl";
		private $_settings;
		private $_template_engine;
	
		public function __construct($settings) {
			$this->_settings = $settings;
			$this->_template_engine = TemplateEngine::getInstance();
		}
	
		public function render() {		
			$current_homepage = $this->_settings->getHomepage();
			
			$website_title = new TextField("website_title", "Website titel", $this->_settings->getWebsiteTitle(), true, false, null);
			$email_field = new TextField("email_address", "Email adres", $this->_settings->getEmailAddress(), false, false, null);
			$homepage_picker = new PagePicker("Homepage", $current_homepage->getId(), "homepage_page_id", "Selecteer pagina", "apply_settings", "pick_homepage");
			$static_dir = new TextField("static_dir", "Static directory", $this->_settings->getStaticDir(), true, false, null);
			$config_dir = new TextField("config_dir", "Configuration directory", $this->_settings->getConfigDir(), true, false, null);
			$upload_dir = new TextField("upload_dir", "Upload directory", $this->_settings->getUploadDir(), true, false, null);
			$frontend_template_dir = new TextField("frontend_template_dir", "Frontend templates directory", $this->_settings->getFrontendTemplateDir(), true, false, null);
			$backend_template_dir = new TextField("backend_template_dir", "Backend templates directory", $this->_settings->getBackendTemplateDir(), true, false, null);
			$component_dir = new TextField("component_dir", "Component directory", $this->_settings->getComponentDir(), true, false, null);
			$frontend_hostname = new TextField("frontend_hostname", "Frontend hostname", $this->_settings->getFrontendHostname(), true, false, null);
			$backend_hostname = new TextField("backend_hostname", "Backend hostname", $this->_settings->getBackendHostname(), true, false, null);
			$smtp_host = new TextField("smtp_host", "SMTP host", $this->_settings->getSmtpHost(), false, false, null);
		
			$this->_template_engine->assign("website_title", $website_title->render());
			$this->_template_engine->assign("email_field", $email_field->render());
			
			if (!is_null($current_homepage)) {
				$this->_template_engine->assign("current_homepage_id", $current_homepage->getId());
				$this->_template_engine->assign("current_homepage_title", $current_homepage->getTitle());
			}
			$this->_template_engine->assign("homepage_picker", $homepage_picker->render());
			$this->_template_engine->assign("root_dir", $root_dir->render());
			$this->_template_engine->assign("static_dir", $static_dir->render());
			$this->_template_engine->assign("config_dir", $config_dir->render());
			$this->_template_engine->assign("upload_dir", $upload_dir->render());
			$this->_template_engine->assign("frontend_template_dir", $frontend_template_dir->render());
			$this->_template_engine->assign("backend_template_dir", $backend_template_dir->render());
			$this->_template_engine->assign("component_dir", $component_dir->render());
			$this->_template_engine->assign("frontend_hostname", $frontend_hostname->render());
			$this->_template_engine->assign("backend_hostname", $backend_hostname->render());
			$this->_template_engine->assign("smtp_host", $smtp_host->render());
			
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}
		
	}

?>