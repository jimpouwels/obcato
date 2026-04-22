<?php

namespace Pageflow\Core\modules\components\visuals\components;

use Pageflow\Core\modules\components\ComponentRequestHandler;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;

class ComponentsDetailsPanel extends Panel {

    private ComponentRequestHandler $componentRequestHandler;

    public function __construct($componentRequestHandler) {
        parent::__construct('Component details');
        $this->componentRequestHandler = $componentRequestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'components/templates/components/details.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('current_element', $this->getCurrentElementData());
        $data->assign('current_module', $this->getCurrentModuleData());
    }

    private function getCurrentElementData(): array {
        $currentElementType = $this->componentRequestHandler->getCurrentElementType();
        if ($currentElementType) {
            $element_data = array();
            $element_data['id'] = $currentElementType->getId();
            $element_data['identifier'] = $currentElementType->getIdentifier();
            $element_data['class'] = $currentElementType->getClassName();
            $element_data['object_file'] = $currentElementType->getDomainObject();
            $element_data['system_default'] = $currentElementType->getSystemDefault();
            return $element_data;
        }
        return array();
    }

    private function getCurrentModuleData(): array {
        $currentModule = $this->componentRequestHandler->getCurrentModule();
        if ($currentModule) {
            $module_data = array();
            $module_data['id'] = $currentModule->getId();
            $module_data['identifier'] = $currentModule->getIdentifier();
            $module_data['title'] = $this->getTextResource($currentModule->getIdentifier() . '_module_title');
            $module_data['class'] = $currentModule->getClass();
            $module_data['system_default'] = $currentModule->isSystemDefault();
            return $module_data;
        }
        return array();
    }
}
