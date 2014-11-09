<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/form.php";
    
    abstract class ElementForm extends Form {

        private $_element;

        public function __construct($element) {
            $this->_element = $element;
        }

        public function loadFields() {
            $template_id = $this->getFieldValue('element_' . $this->_element->getId() . '_template');
            $this->_element->setTemplateId($template_id);
        }
    }

?>