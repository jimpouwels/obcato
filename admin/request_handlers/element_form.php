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
            $include_in_toc = $this->getCheckboxValue('element_' . $this->_element->getId() . '_toc');
            $this->_element->setTemplateId($template_id);
            $this->_element->setIncludeInTableOfContents($include_in_toc == 1 ? true : false);
        }
    }