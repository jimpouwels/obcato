<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . 'database/dao/module_dao.php';

class ModulesListPanel extends Panel {

    private static $TEMPLATE = 'components/modules_list.tpl';
    private $_module_dao;
    private $_components_request_handler;

    public function __construct($components_request_handler) {
        parent::__construct('Modules', 'component-list-fieldset');
        $this->_components_request_handler = $components_request_handler;
        $this->_module_dao = ModuleDao::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/modules_list.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('modules', $this->getModulesData());
    }

    private function getModulesData() {
        $modules_data = array();
        foreach ($this->_module_dao->getAllModules() as $module) {
            $module_data = array();
            $module_data['id'] = $module->getId();
            $module_data['title'] = $this->getTextResource($module->getTitleTextResourceIdentifier());
            $module_data['icon_url'] = '/admin/static.php?file=/modules/' . $module->getIdentifier() . $module->getIconUrl();
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
