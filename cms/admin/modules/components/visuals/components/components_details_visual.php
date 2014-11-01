<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . '/database/dao/module_dao.php';

    class ComponentsDetailsVisual extends Visual {

        private static $TEMPLATE = 'components/details.tpl';
        private $_template_engine;
        private $_component_request_handler;

        public function __construct($component_request_handler) {
            $this->_component_request_handler = $component_request_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign('current_element', $this->getCurrentElementData());
            $this->_template_engine->assign('current_module', $this->getCurrentModuleData());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function getCurrentModuleData() {
            $current_module = $this->_component_request_handler->getCurrentModule();
            if ($current_module) {
                $module_data = array();
                $module_data['id'] = $current_module->getId();
                $module_data['title'] = $current_module->getTitle();
                $module_data['system_default'] = $current_module->isSystemDefault();
                return $module_data;
            }
        }

        private function getCurrentElementData() {
            return null;
        }
    }