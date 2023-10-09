<?php

class ComponentInstallLogPanel extends Panel {

    private $_install_request_handler;

    public function __construct($install_requestHandler) {
        parent::__construct('Log', 'installation-log-fieldset');
        $this->_install_request_handler = $install_requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/installation/component_install_log.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('log_messages', $this->_install_request_handler->getLogMessages());
    }
}
