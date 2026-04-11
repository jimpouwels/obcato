<?php

namespace Obcato\Core\elements\list_element;

use Obcato\Core\request_handlers\ElementForm;

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
        $this->loadListItemOrder();
    }

    public function getListItemsToDelete(): array {
        $listItemsToDelete = array();
        foreach ($this->listElement->getListItems() as $listItem) {
            if ($this->getFieldValue("listitem_" . $listItem->getId() . "_delete")) {
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

    private function loadListItemOrder(): void {
        $itemOrder = $this->getFieldValue('element_' . $this->listElement->getId() . '_list_item_order');
        if (!$itemOrder) {
            return;
        }
        $itemOrderArr = explode(',', $itemOrder);

        $listItemsById = [];
        foreach ($this->listElement->getListItems() as $listItem) {
            $listItemsById[$listItem->getId()] = $listItem;
        }
        for ($i = 0; $i < count($itemOrderArr); $i++) {
            $id = intval(trim($itemOrderArr[$i]));
            if (isset($listItemsById[$id])) {
                $listItemsById[$id]->setOrderNr($i);
            }
        }

        $items = $this->listElement->getListItems();
        usort($items, fn($a, $b) => $a->getOrderNr() <=> $b->getOrderNr());
        $this->listElement->setListItems($items);
    }

}