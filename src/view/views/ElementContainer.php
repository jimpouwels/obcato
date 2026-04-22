<?php

namespace Pageflow\Core\view\views;

use Pageflow\Core\core\BlackBoard;
use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\view\TemplateData;

class ElementContainer extends Panel {

    private array $elements;
    private ElementDao $elementDao;

    public function __construct(array $elements) {
        parent::__construct($this->getTextResource('element_holder_content_title'), 'element_container');
        $this->elements = $elements;
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "element_container.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        if (count($this->elements) > 0) {
            $data->assign("elements", $this->renderElements());
        }
        $data->assign("element_types", $this->getElementTypes());
    }

    private function renderElements(): array {
        $elements = array();
        foreach ($this->elements as $element) {
            $elements[] = $element->getBackendVisual()->render();
        }
        return $elements;
    }

    private function getElementTypes(): array {
        $elementTypes = array();
        foreach ($this->elementDao->getElementTypes() as $elementType) {
            $typeData = array();
            $typeData['id'] = $elementType->getId();
            $typeData['name'] = $this->getTextResource($elementType->getIdentifier() . '_label');
            $typeData['identifier'] = $elementType->getIdentifier();
            $typeData['icon_url'] = BlackBoard::getElementFileUrl($elementType->getIdentifier(), 'img/' . $elementType->getIdentifier() . '.png');
            $elementTypes[] = $typeData;
        }
        return $elementTypes;
    }
}