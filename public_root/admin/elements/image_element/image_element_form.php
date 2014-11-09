<?php
    
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "request_handlers/element_form.php";

    class ImageElementForm extends ElementForm {

        private $_image_element;

        public function __construct($image_element) {
            parent::__construct($image_element);
            $this->_image_element = $image_element;
        }

        public function loadFields() {
            parent::loadFields();
            $this->_image_element->setTitle($this->getFieldValue('element_' . $this->_image_element->getId() . '_title'));
            $this->_image_element->setAlternativeText($this->getFieldValue('element_' . $this->_image_element->getId() . '_alternative_text'));
            $this->_image_element->setAlign($this->getFieldValue('element_' . $this->_image_element->getId() . '_align'));
            $this->_image_element->setImageId($this->getFieldValue('image_image_ref_' . $this->_image_element->getId()));
        }

    }