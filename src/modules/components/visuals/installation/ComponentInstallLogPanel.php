<?php

namespace Obcato\Core\modules\components\visuals\installation;

use Obcato\Core\modules\components\InstallRequestHandler;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class ComponentInstallLogPanel extends Panel {

    private InstallRequestHandler $installRequestHandler;

    public function __construct($install_requestHandler) {
        parent::__construct('Log', 'installation-log-fieldset');
        $this->installRequestHandler = $install_requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/installation/component_install_log.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('log_messages', $this->installRequestHandler->getLogMessages());
    }
}
