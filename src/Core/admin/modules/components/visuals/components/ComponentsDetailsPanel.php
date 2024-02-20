<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

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
