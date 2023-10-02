<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';

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
        if ($this->hasErrors()) {
            throw new FormException('ElementHolder form contains errors');
        }
    }

}