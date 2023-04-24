<?php
    defined("_ACCESS") or die;

    class DomainSettingsPanel extends Panel {

        private $_settings;

        public function __construct($settings) {
            parent::__construct('Domein instellingen');
            $this->_settings = $settings;
        }

        public function getPanelContentTemplate(): string {
            return  "modules/settings/domain_settings_panel.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $frontend_hostname = new TextField("frontend_hostname", "Frontend hostname", $this->_settings->getFrontendHostname(), true, false, null);
            $backend_hostname = new TextField("backend_hostname", "Backend hostname", $this->_settings->getBackendHostname(), true, false, null);
            $smtp_host = new TextField("smtp_host", "SMTP host", $this->_settings->getSmtpHost(), false, false, null);

            $data->assign("frontend_hostname", $frontend_hostname->render());
            $data->assign("backend_hostname", $backend_hostname->render());
            $data->assign("smtp_host", $smtp_host->render());
        }
    }
