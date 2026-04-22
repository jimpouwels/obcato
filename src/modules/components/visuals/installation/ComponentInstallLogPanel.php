<?php

namespace Pageflow\Core\modules\components\visuals\installation;

use Pageflow\Core\modules\components\InstallRequestHandler;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;

class ComponentInstallLogPanel extends Panel {

    private InstallRequestHandler $installRequestHandler;

    public function __construct($install_requestHandler) {
        parent::__construct('Log', 'installation-log-fieldset');
        $this->installRequestHandler = $install_requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'components/templates/installation/component_install_log.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('log_messages', $this->installRequestHandler->getLogMessages());
    }
}
