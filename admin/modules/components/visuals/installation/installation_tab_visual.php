<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'modules/components/visuals/installation/component_install_form_panel.php';
    require_once CMS_ROOT . 'modules/components/visuals/installation/component_install_log_panel.php';

    class InstallationTabVisual extends Visual {

        private static $TEMPLATE = 'installation/root.tpl';
        private $_template_engine;
        private $_install_request_handler;

        public function __construct($install_request_handler) {
            $this->_install_request_handler = $install_request_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign("component_install_form", $this->renderComponentInstallFormPanel());
            $this->_template_engine->assign("component_install_log", $this->renderComponentInstallLogPanel());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
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
