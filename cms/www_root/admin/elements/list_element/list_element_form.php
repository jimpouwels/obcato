<?php
    // No direct access
    defined("_ACCESS") or die;

    require_once "pre_handlers/form.php";

    class ListElementForm extends Form {

        private $_list_element;

        public function __construct($list_element) {
            $this->_list_element = $list_element;
        }

        public function LoadFields()
        {
            $this->_list_element->setTitle($this->getFieldValue('element_' . $this->_list_element->getId() . '_title'));
            $this->_list_element->setTemplateId($this->getFieldValue('element_' . $this->_list_element->getId() . '_template'));
        }
    }