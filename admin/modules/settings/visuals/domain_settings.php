<?php
    defined("_ACCESS") or die;

    class DomainSettingsPanel extends Panel {

        private static $TEMPLATE = "modules/settings/domain_settings_panel.tpl";
        private $_settings;

        public function __construct($settings) {
            parent::__construct('Domein instellingen');
            $this->_settings = $settings;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $frontend_hostname = new TextField("frontend_hostname", "Frontend hostname", $this->_settings->getFrontendHostname(), true, false, null);
            $backend_hostname = new TextField("backend_hostname", "Backend hostname", $this->_settings->getBackendHostname(), true, false, null);
            $smtp_host = new TextField("smtp_host", "SMTP host", $this->_settings->getSmtpHost(), false, false, null);

            $this->getTemplateEngine()->assign("frontend_hostname", $frontend_hostname->render());
            $this->getTemplateEngine()->assign("backend_hostname", $backend_hostname->render());
            $this->getTemplateEngine()->assign("smtp_host", $smtp_host->render());

            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }
