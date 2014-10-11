<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/forms/form.php";

    class TextElementForm extends Form {

        private $_text_element;

        public function __construct($text_element) {
            $this->_text_element = $text_element;
        }

        public function loadFields()
        {
            $this->_text_element->setTitle($this->getFieldValue('element_' . $this->_text_element->getId() . '_title'));
            $this->_text_element->setText($this->getFieldValue('element_' . $this->_text_element->getId() . '_text'));
            $this->_text_element->setTemplateId($this->getFieldValue('element_' . $this->_text_element->getId() . '_template'));
        }
    }
?>