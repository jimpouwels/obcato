<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . '/database/dao/module_dao.php';

    class ComponentsListVisual extends Visual {

        private static $TEMPLATE = 'components/list.tpl';
        private $_module_dao;
        private $_template_engine;

        public function __construct() {
            $this->_module_dao = ModuleDao::getInstance();
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
                $module_data['title'] = $module->getTitle();
                $module_data['icon_url'] = '/admin/static.php?file=/modules/' . $module->getIdentifier() . $module->getIconUrl();
                $modules_data[] = $module_data;
            }
            return $modules_data;
        }

        private function getElementsData() {
            return array();
        }
    }