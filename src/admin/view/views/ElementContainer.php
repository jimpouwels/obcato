<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class ElementContainer extends Panel {

    private array $elements;

    public function __construct(TemplateEngine $templateEngine, array $elements) {
        parent::__construct($templateEngine, $this->getTextResource('element_holder_content_title'), 'element_container');
        $this->elements = $elements;
    }

    public function getPanelContentTemplate(): string {
        return "system/element_container.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        if (count($this->elements) > 0) {
            $data->assign("elements", $this->renderElements());
        } else {
            $data->assign("message", $this->renderInformationMessage());
        }
    }

    private function renderInformationMessage(): string {
        $information_message = new InformationMessage($this->getTemplateEngine(), $this->getTextResource('no_elements_found_message'));
        return $information_message->render();
    }

    private function renderElements(): array {
        $elements = array();
        foreach ($this->elements as $element) {
            $elements[] = $element->getBackendVisual()->render();
        }
        return $elements;
    }
}