<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class ComponentInstallLogPanel extends Panel {

    private InstallRequestHandler $installRequestHandler;

    public function __construct(TemplateEngine $templateEngine, $install_requestHandler) {
        parent::__construct($templateEngine, 'Log', 'installation-log-fieldset');
        $this->installRequestHandler = $install_requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/installation/component_install_log.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('log_messages', $this->installRequestHandler->getLogMessages());
    }
}
