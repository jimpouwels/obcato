<?php
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';

class ElementsListPanel extends Panel {

    private ElementDao $elementDao;
    private ComponentRequestHandler $componentsRequestHandler;

    public function __construct($components_requestHandler) {
        parent::__construct('Elementen', 'component-list-fieldset');
        $this->componentsRequestHandler = $components_requestHandler;
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/components/elements_list.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('elements', $this->getElementsData());
    }

    private function getElementsData(): array {
        $elements_data = array();
        foreach ($this->elementDao->getElementTypes() as $element_type) {
            $element_data = array();
            $element_data['id'] = $element_type->getId();
            $element_data['name'] = $this->getTextResource($element_type->getIdentifier() . '_label');
            $element_data['icon_url'] = '/admin/static.php?file=/elements/' . $element_type->getIdentifier() . $element_type->getIconUrl();
            $element_data['is_current'] = $this->isCurrentElement($element_type);
            $elements_data[] = $element_data;
        }
        return $elements_data;
    }

    private function isCurrentElement($element) {
        $current_element = $this->componentsRequestHandler->getCurrentElementType();
        return $current_element && $current_element->getId() == $element->getId();
    }
}
