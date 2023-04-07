<?php
    defined('_ACCESS') or die;

    class ComponentInstallLogPanel extends Panel {

        private static $TEMPLATE = 'installation/component_install_log.tpl';
        private $_install_request_handler;

        public function __construct($install_request_handler) {
            parent::__construct('Log', 'installation-log-fieldset');
            $this->_install_request_handler = $install_request_handler;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign('log_messages', $this->_install_request_handler->getLogMessages());
            return $this->getTemplateEngine()->fetch('modules/components/' . self::$TEMPLATE);
        }
    }
