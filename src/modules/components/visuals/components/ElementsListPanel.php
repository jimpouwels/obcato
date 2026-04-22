<?php

namespace Pageflow\Core\modules\components\visuals\components;

use Pageflow\Core\core\BlackBoard;
use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\modules\components\ComponentRequestHandler;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;

class ElementsListPanel extends Panel {

    private ElementDao $elementDao;
    private ComponentRequestHandler $componentsRequestHandler;

    public function __construct($requestHandler) {
        parent::__construct('Elementen', 'component-list-fieldset');
        $this->componentsRequestHandler = $requestHandler;
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'components/templates/components/elements_list.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('elements', $this->getElementsData());
    }

    private function getElementsData(): array {
        $elementsData = array();
        foreach ($this->elementDao->getElementTypes() as $elementType) {
            $elementData = array();
            $elementData['id'] = $elementType->getId();
            $elementData['name'] = $this->getTextResource($elementType->getIdentifier() . '_label');
            $elementData['icon_url'] = BlackBoard::getElementFileUrl($elementType->getIdentifier(), 'img/' . $elementType->getIdentifier() . '.png');
            $elementData['is_current'] = $this->isCurrentElement($elementType);
            $elementsData[] = $elementData;
        }
        return $elementsData;
    }

    private function isCurrentElement($element): bool {
        $currentElement = $this->componentsRequestHandler->getCurrentElementType();
        return $currentElement && $currentElement->getId() == $element->getId();
    }
}
