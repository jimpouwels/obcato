<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . "request_handlers/element_form.php";

class IFrameElementForm extends ElementForm {

    private IFrameElement $_iframe_element;

    public function __construct(IFrameElement $iframe_element) {
        parent::__construct($iframe_element);
        $this->_iframe_element = $iframe_element;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->_iframe_element->setTitle($this->getFieldValue('element_' . $this->_iframe_element->getId() . '_title'));
        $this->_iframe_element->setUrl($this->getFieldValue('element_' . $this->_iframe_element->getId() . '_url'));
        $this->_iframe_element->setWidth($this->getNumber('element_' . $this->_iframe_element->getId() . '_width', $this->getTextResource("form_invalid_number_error")));
        $this->_iframe_element->setHeight($this->getNumber('element_' . $this->_iframe_element->getId() . '_height', $this->getTextResource("form_invalid_number_error")));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}