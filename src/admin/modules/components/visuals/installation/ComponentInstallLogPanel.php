<?php

namespace Obcato\Core\admin\modules\components\visuals\installation;

use Obcato\Core\admin\modules\components\InstallRequestHandler;
use Obcato\Core\admin\view\TemplateData;
use Obcato\Core\admin\view\views\Panel;

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
