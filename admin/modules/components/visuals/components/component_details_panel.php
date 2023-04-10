<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/module_dao.php';

    class ComponentsDetailsPanel extends Panel {

        private static $TEMPLATE = 'components/details.tpl';
        private $_component_request_handler;

        public function __construct($component_request_handler) {
            parent::__construct('Component details');
            $this->_component_request_handler = $component_request_handler;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign('current_element', $this->getCurrentElementData());
            $this->getTemplateEngine()->assign('current_module', $this->getCurrentModuleData());
            return $this->getTemplateEngine()->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function getCurrentModuleData() {
            $current_module = $this->_component_request_handler->getCurrentModule();
            if ($current_module) {
                $module_data = array();
                $module_data['id'] = $current_module->getId();
                $module_data['identifier'] = $current_module->getIdentifier();
                $module_data['title'] = $this->getTextResource($current_module->getTitleTextResourceIdentifier());
                $module_data['class'] = $current_module->getClass();
                $module_data['system_default'] = $current_module->isSystemDefault();
                return $module_data;
            }
        }

        private function getCurrentElementData() {
            $current_element = $this->_component_request_handler->getCurrentElement();
            if ($current_element) {
                $element_data = array();
                $element_data['id'] = $current_element->getId();
                $element_data['identifier'] = $current_element->getIdentifier();
                $element_data['name'] = $current_element->getName();
                $element_data['class'] = $current_element->getClassName();
                $element_data['object_file'] = $current_element->getDomainObject();
                $element_data['system_default'] = $current_element->getSystemDefault();
                return $element_data;
            }
        }
    }
