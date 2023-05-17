<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . 'database/dao/element_dao.php';
    
    abstract class ElementHolderForm extends Form {

        private ElementHolder $_element_holder;

        public function __construct(ElementHolder $element_holder) {
            $this->_element_holder = $element_holder;
        }

        public function loadFields(): void {
            $item_order = $this->getFieldValue('draggable_order');
            $item_order_arr = array();
            if ($item_order) {
                $item_order_arr = explode(',', $item_order);
            }
            foreach ($this->_element_holder->getElements() as $element) {
                if (count($item_order_arr) > 0) {
                    $element->setOrderNr(array_search($element->getId(), $item_order_arr));
                }
            }
            if ($this->hasErrors()) {
                throw new FormException('ElementHolder form contains errors');
            }
        }

    }