<?php
require_once CMS_ROOT . "/request_handlers/ElementForm.php";

class ListElementForm extends ElementForm {

    private ListElement $listElement;

    public function __construct($listElement) {
        parent::__construct($listElement);
        $this->listElement = $listElement;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->listElement->setTitle($this->getFieldValue('element_' . $this->listElement->getId() . '_title'));
        $this->loadListItemsFields();
    }

    public function getListItemsToDelete(): array {
        $listItemsToDelete = array();
        foreach ($this->listElement->getListItems() as $listItem) {
            if (!is_null($this->getFieldValue("listitem_" . $listItem->getId() . "_delete"))) {
                $listItemsToDelete[] = $listItem;
            }
        }
        return $listItemsToDelete;
    }

    private function loadListItemsFields(): void {
        foreach ($this->listElement->getListItems() as $listItem) {
            $listItem->setText($this->getFieldValue("listitem_" . $listItem->getId() . "_text"));
        }
    }

}