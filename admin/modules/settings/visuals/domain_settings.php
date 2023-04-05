<?php
    defined("_ACCESS") or die;

    class DomainSettingsPanel extends Panel {

        private static $TEMPLATE = "modules/settings/domain_settings_panel.tpl";
        private $_settings;
        private $_template_engine;

        public function __construct($settings) {
            parent::__construct('Domein instellingen');
            $this->_settings = $settings;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $frontend_hostname = new TextField("frontend_hostname", "Frontend hostname", $this->_settings->getFrontendHostname(), true, false, null);
            $backend_hostname = new TextField("backend_hostname", "Backend hostname", $this->_settings->getBackendHostname(), true, false, null);
            $smtp_host = new TextField("smtp_host", "SMTP host", $this->_settings->getSmtpHost(), false, false, null);

            $this->_template_engine->assign("frontend_hostname", $frontend_hostname->render());
            $this->_template_engine->assign("backend_hostname", $backend_hostname->render());
            $this->_template_engine->assign("smtp_host", $smtp_host->render());

            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    }
