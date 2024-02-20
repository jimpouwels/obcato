<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class InstallationTabVisual extends Visual {

    private InstallRequestHandler $installRequestHandler;

    public function __construct(TemplateEngine $templateEngine, $requestHandler) {
        parent::__construct($templateEngine);
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
        $componentInstallFormPanel = new ComponentInstallFormPanel($this->getTemplateEngine());
        return $componentInstallFormPanel->render();
    }

    private function renderComponentInstallLogPanel(): string {
        $component_install_log = new ComponentInstallLogPanel($this->getTemplateEngine(), $this->installRequestHandler);
        return $component_install_log->render();
    }
}