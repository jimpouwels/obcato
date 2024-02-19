<?php
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';

class ElementsListPanel extends Panel {

    private ElementDao $elementDao;
    private ComponentRequestHandler $componentsRequestHandler;

    public function __construct(TemplateEngine $templateEngine, $requestHandler) {
        parent::__construct($templateEngine, 'Elementen', 'component-list-fieldset');
        $this->componentsRequestHandler = $requestHandler;
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/components/elements_list.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('elements', $this->getElementsData());
    }

    private function getElementsData(): array {
        $elementsData = array();
        foreach ($this->elementDao->getElementTypes() as $elementType) {
            $elementData = array();
            $elementData['id'] = $elementType->getId();
            $elementData['name'] = $this->getTextResource($elementType->getIdentifier() . '_label');
            $elementData['icon_url'] = '/admin/static.php?file=/elements/' . $elementType->getIdentifier() . "/img/" . $elementType->getIdentifier() . ".png";
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
