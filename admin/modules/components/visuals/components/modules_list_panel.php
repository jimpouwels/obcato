<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/module_dao.php';

    class ModulesListPanel extends Panel {

        private static $TEMPLATE = 'components/modules_list.tpl';
        private $_module_dao;
        private $_template_engine;
        private $_components_request_handler;

        public function __construct($components_request_handler) {
            parent::__construct('Modules', 'component-list-fieldset');
            $this->_components_request_handler = $components_request_handler;
            $this->_module_dao = ModuleDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign('modules', $this->getModulesData());
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

        private function isCurrentModule($module) {
            $current_module = $this->_components_request_handler->getCurrentModule();
            return $current_module && $current_module->getId() == $module->getId();
        }
    }
