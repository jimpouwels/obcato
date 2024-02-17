<?php
require_once CMS_ROOT . '/database/dao/ModuleDaoMysql.php';

class ModulesListPanel extends Panel {

    private ModuleDao $moduleDao;
    private ComponentRequestHandler $componentsRequestHandler;

    public function __construct($components_requestHandler) {
        parent::__construct('Modules', 'component-list-fieldset');
        $this->componentsRequestHandler = $components_requestHandler;
        $this->moduleDao = ModuleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/components/modules_list.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('modules', $this->getModulesData());
    }

    private function getModulesData(): array {
        $modules_data = array();
        foreach ($this->moduleDao->getAllModules() as $module) {
            $module_data = array();
            $module_data['id'] = $module->getId();
            $module_data['title'] = $this->getTextResource($module->getIdentifier() . '_module_title');
            $module_data['icon_url'] = '/admin/static.php?file=/modules/' . $module->getIdentifier() . '/img/' . $module->getIdentifier() . '.png';
            $module_data['is_current'] = $this->isCurrentModule($module);
            $modules_data[] = $module_data;
        }
        return $modules_data;
    }

    private function isCurrentModule($module): bool {
        $current_module = $this->componentsRequestHandler->getCurrentModule();
        return $current_module && $current_module->getId() == $module->getId();
    }
}
