<?php
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';

class ElementsListPanel extends Panel {

    private $_element_dao;
    private $_components_request_handler;

    public function __construct($components_request_handler) {
        parent::__construct('Elementen', 'component-list-fieldset');
        $this->_components_request_handler = $components_request_handler;
        $this->_element_dao = ElementDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/elements_list.tpl';
    }


    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('elements', $this->getElementsData());
    }

    private function getElementsData() {
        $elements_data = array();
        foreach ($this->_element_dao->getElementTypes() as $element_type) {
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
        $current_element = $this->_components_request_handler->getCurrentElement();
        return $current_element && $current_element->getId() == $element->getId();
    }
}
