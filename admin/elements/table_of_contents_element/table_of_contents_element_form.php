<?php
    
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "request_handlers/element_form.php";
    require_once CMS_ROOT . "utilities/date_utility.php";

    class TableOfContentsElementForm extends ElementForm {

        private $_table_of_contents_element;

        public function __construct($_table_of_contents_element) {
            parent::__construct($_table_of_contents_element);
            $this->_table_of_contents_element = $_table_of_contents_element;
        }

        public function loadFields() {
            $element_id = $this->_table_of_contents_element->getId();
            $title = $this->getFieldValue('element_' . $element_id . '_title');
            if ($this->hasErrors())
                throw new FormException();
            else {
                parent::loadFields();
                $this->_table_of_contents_element->setTitle($title);
            }
        }
    }