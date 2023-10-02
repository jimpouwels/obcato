<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/request_handlers/element_form.php";

class ImageElementForm extends ElementForm {

    private ImageElement $_image_element;

    public function __construct(ImageElement $image_element) {
        parent::__construct($image_element);
        $this->_image_element = $image_element;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->_image_element->setTitle($this->getFieldValue('element_' . $this->_image_element->getId() . '_title'));
        $this->_image_element->setAlign($this->getFieldValue('element_' . $this->_image_element->getId() . '_align'));
        $this->_image_element->setImageId($this->getNumber('image_image_ref_' . $this->_image_element->getId(), $this->getTextResource("form_invalid_number_error")));
        $this->_image_element->setWidth($this->getNumber('element_' . $this->_image_element->getId() . '_width', $this->getTextResource("form_invalid_number_error")));
        $this->_image_element->setHeight($this->getNumber('element_' . $this->_image_element->getId() . '_height', $this->getTextResource("form_invalid_number_error")));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}