<?php
require_once CMS_ROOT . '/modules/components/visuals/installation/component_install_form_panel.php';
require_once CMS_ROOT . '/modules/components/visuals/installation/component_install_log_panel.php';

class InstallationTabVisual extends Visual {

    private $_install_request_handler;

    public function __construct($install_request_handler) {
        parent::__construct();
        $this->_install_request_handler = $install_request_handler;
    }

    public function getTemplateFilename(): string {
        return 'modules/components/installation/root.tpl';
    }

    public function load(): void {
        $this->assign("component_install_form", $this->renderComponentInstallFormPanel());
        $this->assign("component_install_log", $this->renderComponentInstallLogPanel());
    }

    private function renderComponentInstallFormPanel() {
        $component_install_form = new ComponentInstallFormPanel();
        return $component_install_form->render();
    }

    private function renderComponentInstallLogPanel() {
        $component_install_log = new ComponentInstallLogPanel($this->_install_request_handler);
        return $component_install_log->render();
    }
}
