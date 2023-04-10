<?php
    defined("_ACCESS") or die;

    class DirectorySettingsPanel extends Panel {

        private static $TEMPLATE = "modules/settings/directory_settings_panel.tpl";
        private $_settings;

        public function __construct($settings) {
            parent::__construct('Directory instellingen');
            $this->_settings = $settings;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $cms_root_dir = new TextField("cms_root_dir", "CMS Root directory", $this->_settings->getCmsRootDir(), true, false, null);
            $public_root_dir = new TextField("public_root_dir", "Public Root directory", $this->_settings->getPublicRootDir(), true, false, null);
            $static_dir = new TextField("static_dir", "Static directory", $this->_settings->getStaticDir(), true, false, null);
            $config_dir = new TextField("config_dir", "Configuration directory", $this->_settings->getConfigDir(), true, false, null);
            $upload_dir = new TextField("upload_dir", "Upload directory", $this->_settings->getUploadDir(), true, false, null);
            $frontend_template_dir = new TextField("frontend_template_dir", "Frontend templates directory", $this->_settings->getFrontendTemplateDir(), true, false, null);
            $backend_template_dir = new TextField("backend_template_dir", "Backend templates directory", $this->_settings->getBackendTemplateDir(), true, false, null);
            $component_dir = new TextField("component_dir", "Component directory", $this->_settings->getComponentDir(), true, false, null);

            $this->getTemplateEngine()->assign("cms_root_dir", $cms_root_dir->render());
            $this->getTemplateEngine()->assign("public_root_dir", $public_root_dir->render());
            $this->getTemplateEngine()->assign("static_dir", $static_dir->render());
            $this->getTemplateEngine()->assign("config_dir", $config_dir->render());
            $this->getTemplateEngine()->assign("upload_dir", $upload_dir->render());
            $this->getTemplateEngine()->assign("frontend_template_dir", $frontend_template_dir->render());
            $this->getTemplateEngine()->assign("backend_template_dir", $backend_template_dir->render());
            $this->getTemplateEngine()->assign("component_dir", $component_dir->render());

            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

    }

?>
