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
        $frontendHostname = new TextField("frontend_hostname", "settings_form_frontend_hostname_field", $this->settings->getFrontendHostname(), true, false, null);
        $backendHostname = new TextField("backend_hostname", "settings_form_backend_hostname_field", $this->settings->getBackendHostname(), true, false, null);
        $smtpHost = new TextField("smtp_host", "settings_form_backend_smtp_host_field", $this->settings->getSmtpHost(), false, false, null);

        $data->assign("frontend_hostname", $frontendHostname->render());
        $data->assign("backend_hostname", $backendHostname->render());
        $data->assign("smtp_host", $smtpHost->render());
    }
}
