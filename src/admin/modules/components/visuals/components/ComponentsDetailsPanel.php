<?php

namespace Obcato\Core\admin\modules\components\visuals\components;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\components\ComponentRequestHandler;
use Obcato\Core\admin\view\views\Panel;

class ComponentsDetailsPanel extends Panel {

    private ComponentRequestHandler $componentRequestHandler;

    public function __construct(TemplateEngine $templateEngine, $componentRequestHandler) {
        parent::__construct($templateEngine, 'Component details');
        $this->componentRequestHandler = $componentRequestHandler;
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/components/details.tpl';
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
