<?php
require_once CMS_ROOT . '/database/dao/ModuleDaoMysql.php';

class ComponentsDetailsPanel extends Panel {

    private ComponentRequestHandler $componentRequestHandler;

    public function __construct($component_requestHandler) {
        parent::__construct('Component details');
        $this->componentRequestHandler = $component_requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/components/details.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('current_element', $this->getCurrentElementData());
        $data->assign('current_module', $this->getCurrentModuleData());
    }

    private function getCurrentElementData(): array {
        $current_element = $this->componentRequestHandler->getCurrentElementType();
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
        return array();
    }

    private function getCurrentModuleData(): array {
        $current_module = $this->componentRequestHandler->getCurrentModule();
        if ($current_module) {
            $module_data = array();
            $module_data['id'] = $current_module->getId();
            $module_data['identifier'] = $current_module->getIdentifier();
            $module_data['title'] = $this->getTextResource($current_module->getIdentifier() . '_module_title');
            $module_data['class'] = $current_module->getClass();
            $module_data['system_default'] = $current_module->isSystemDefault();
            return $module_data;
        }
        return array();
    }
}
