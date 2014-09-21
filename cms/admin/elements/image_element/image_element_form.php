<?php
    // No direct access
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/view/form.php";

    class ImageElementForm extends Form {

        private $_image_element;

        public function __construct($image_element) {
            $this->_image_element = $image_element;
        }

        public function loadFields()
        {
            $this->_image_element->setTitle($this->getFieldValue('element_' . $this->_image_element->getId() . '_title'));
            $this->_image_element->setAlternativeText($this->getFieldValue('element_' . $this->_image_element->getId() . '_alternative_text'));
            $this->_image_element->setAlign($this->getFieldValue('element_' . $this->_image_element->getId() . '_align'));
            $this->_image_element->setTemplateId($this->getFieldValue('element_' . $this->_image_element->getId() . '_template'));
            $this->_image_element->setImageId($this->getFieldValue('image_image_ref_' . $this->_image_element->getId()));
        }

    }