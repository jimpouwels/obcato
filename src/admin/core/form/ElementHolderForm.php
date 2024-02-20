<?php

namespace Obcato\Core\admin\core\form;

use Obcato\Core\admin\core\model\ElementHolder;

class ElementHolderForm extends Form {

    private ElementHolder $elementHolder;

    public function __construct(ElementHolder $elementHolder) {
        $this->elementHolder = $elementHolder;
    }

    public function loadFields(): void {
        $itemOrder = $this->getFieldValue('draggable_order');
        $itemOrderArr = array();
        if ($itemOrder) {
            $itemOrderArr = explode(',', $itemOrder);
        }
        $orderNr = 0;
        foreach ($itemOrderArr as $item) {
            foreach ($this->elementHolder->getElements() as $element) {
                if ($element->getId() == $item) {
                    $element->setOrderNr($orderNr++);
                    break;
                }
            }
        }
    }

}