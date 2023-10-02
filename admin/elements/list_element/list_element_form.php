<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "request_handlers/element_form.php";

class ListElementForm extends ElementForm {

    private ListElement $_list_element;

    public function __construct($list_element) {
        parent::__construct($list_element);
        $this->_list_element = $list_element;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->_list_element->setTitle($this->getFieldValue('element_' . $this->_list_element->getId() . '_title'));
        $this->loadListItemsFields();
    }

    public function getListItemsToDelete(): array {
        $list_items_to_delete = array();
        foreach ($this->_list_element->getListItems() as $list_item) {
            if (!is_null($this->getFieldValue("listitem_" . $list_item->getId() . "_delete"))) {
                $list_items_to_delete[] = $list_item;
            }
        }
        return $list_items_to_delete;
    }

    private function loadListItemsFields(): void {
        foreach ($this->_list_element->getListItems() as $list_item) {
            $list_item->setText($this->getFieldValue("listitem_" . $list_item->getId() . "_text"));
        }
    }

}