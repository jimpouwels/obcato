<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/module_dao.php';
    require_once CMS_ROOT . 'database/dao/element_dao.php';

    class ComponentsListVisual extends Visual {

        private static $TEMPLATE = 'components/list.tpl';
        private $_module_dao;
        private $_element_dao;
        private $_template_engine;
        private $_components_request_handler;

        public function __construct($components_request_handler) {
            $this->_components_request_handler = $components_request_handler;
            $this->_module_dao = ModuleDao::getInstance();
            $this->_element_dao = ElementDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign('modules', $this->getModulesData());
            $this->_template_engine->assign('elements', $this->getElementsData());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function getModulesData() {
            $modules_data = array();
            foreach ($this->_module_dao->getAllModules() as $module) {
                $module_data = array();
                $module_data['id'] = $module->getId();
                $module_data['title'] = $this->getTextResource($module->getTitleTextResourceIdentifier());
                $module_data['icon_url'] = '/admin/static.php?file=/modules/'. $module->getIdentifier() . $module->getIconUrl();
                $module_data['is_current'] = $this->isCurrentModule($module);
                $modules_data[] = $module_data;
            }
            return $modules_data;
        }

        private function getElementsData() {
            $elements_data = array();
            foreach ($this->_element_dao->getElementTypes() as $element_type) {
                $element_data = array();
                $element_data['id'] = $element_type->getId();
                $element_data['name'] = $element_type->getName();
                $element_data['icon_url'] = '/admin/static.php?file=/elements/' . $element_type->getIdentifier() . $element_type->getIconUrl();
                $element_data['is_current'] = $this->isCurrentElement($element_type);
                $elements_data[] = $element_data;
            }
            return $elements_data;
        }

        private function isCurrentModule($module) {
            $current_module = $this->_components_request_handler->getCurrentModule();
            return $current_module && $current_module->getId() == $module->getId();
        }

        private function isCurrentElement($element) {
            $current_element = $this->_components_request_handler->getCurrentElement();
            return $current_element && $current_element->getId() == $element->getId();
        }
    }