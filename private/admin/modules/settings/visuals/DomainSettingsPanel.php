<?php

class DomainSettingsPanel extends Panel {

    private Settings $settings;

    public function __construct($settings) {
        parent::__construct('Domein instellingen');
        $this->settings = $settings;
    }

    public function getPanelContentTemplate(): string {
        return "modules/settings/domain_settings_panel.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $frontend_hostname = new TextField("frontend_hostname", "settings_form_frontend_hostname_field", $this->settings->getFrontendHostname(), true, false, null);
        $backend_hostname = new TextField("backend_hostname", "settings_form_backend_hostname_field", $this->settings->getBackendHostname(), true, false, null);
        $smtp_host = new TextField("smtp_host", "settings_form_backend_smtp_host_field", $this->settings->getSmtpHost(), false, false, null);

        $data->assign("frontend_hostname", $frontend_hostname->render());
        $data->assign("backend_hostname", $backend_hostname->render());
        $data->assign("smtp_host", $smtp_host->render());
    }
}
