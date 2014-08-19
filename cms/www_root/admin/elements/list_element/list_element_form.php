<?php
    // No direct access
    defined("_ACCESS") or die;

    require_once "pre_handlers/form.php";

    class ListElementForm extends Form {

        private $_list_element;

        public function __construct($list_element) {
            $this->_list_element = $list_element;
        }

        public function loadFields()
        {
            $this->_list_element->setTitle($this->getFieldValue('element_' . $this->_list_element->getId() . '_title'));
            $this->_list_element->setTemplateId($this->getFieldValue('element_' . $this->_list_element->getId() . '_template'));
            $this->loadListItemsFields();
        }

        public function getListItemsToDelete() {
            $list_items_to_delete = array();
            foreach ($this->_list_element->getListItems() as $list_item)
                if (!is_null($this->getFieldValue("listitem_" . $list_item->getId() . "_delete")))
                    $list_items_to_delete[] = $list_item;
            return $list_items_to_delete;
        }

        private function loadListItemsFields()
        {
            foreach ($this->_list_element->getListItems() as $list_item)
                $list_item->setText($this->getFieldValue("listitem_" . $list_item->getId() . "_text"));
        }

    }