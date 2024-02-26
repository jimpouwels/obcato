<?php

namespace Obcato\Core\modules\components\visuals\installation;

use Obcato\Core\modules\components\InstallRequestHandler;
use Obcato\Core\view\views\Visual;

class InstallationTabVisual extends Visual {

    private InstallRequestHandler $installRequestHandler;

    public function __construct(InstallRequestHandler $requestHandler) {
        parent::__construct();
        $this->installRequestHandler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return 'modules/components/installation/root.tpl';
    }

    public function load(): void {
        $this->assign("component_install_form", $this->renderComponentInstallFormPanel());
        $this->assign("component_install_log", $this->renderComponentInstallLogPanel());
    }

    private function renderComponentInstallFormPanel(): string {
        $componentInstallFormPanel = new ComponentInstallFormPanel();
        return $componentInstallFormPanel->render();
    }

    private function renderComponentInstallLogPanel(): string {
        $component_install_log = new ComponentInstallLogPanel($this->installRequestHandler);
        return $component_install_log->render();
    }
}
