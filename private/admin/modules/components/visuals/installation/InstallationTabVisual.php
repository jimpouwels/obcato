<?php

require_once CMS_ROOT . '/modules/components/visuals/installation/ComponentInstallFormPanel.php';
require_once CMS_ROOT . '/modules/components/visuals/installation/ComponentInstallLogPanel.php';

class InstallationTabVisual extends Visual {

    private InstallRequestHandler $installRequestHandler;

    public function __construct($install_requestHandler) {
        parent::__construct();
        $this->installRequestHandler = $install_requestHandler;
    }

    public function getTemplateFilename(): string {
        return 'modules/components/installation/root.tpl';
    }

    public function load(): void {
        $this->assign("component_install_form", $this->renderComponentInstallFormPanel());
        $this->assign("component_install_log", $this->renderComponentInstallLogPanel());
    }

    private function renderComponentInstallFormPanel(): string {
        $component_install_form = new ComponentInstallFormPanel();
        return $component_install_form->render();
    }

    private function renderComponentInstallLogPanel(): string {
        $component_install_log = new ComponentInstallLogPanel($this->installRequestHandler);
        return $component_install_log->render();
    }
}
