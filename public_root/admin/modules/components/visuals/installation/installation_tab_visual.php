<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . '/modules/components/visuals/installation/component_install_form_visual.php';

    class InstallationTabVisual extends Visual {

        private static $TEMPLATE = 'installation/root.tpl';
        private $_template_engine;
        private $_install_request_handler;

        public function __construct($install_request_handler) {
            $this->_install_request_handler = $install_request_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign("component_install_form", $this->renderComponentInstallForm());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function renderComponentInstallForm() {
            $component_install_form = new ComponentInstallFormVisual($this->_install_request_handler);
            return $component_install_form->render();
        }
    }