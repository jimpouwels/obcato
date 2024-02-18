<?php
require_once CMS_ROOT . "/request_handlers/ElementForm.php";

class IFrameElementForm extends ElementForm {

    private IFrameElement $iframeElement;

    public function __construct(IFrameElement $iframeElement) {
        parent::__construct($iframeElement);
        $this->iframeElement = $iframeElement;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->iframeElement->setTitle($this->getFieldValue('element_' . $this->iframeElement->getId() . '_title'));
        $this->iframeElement->setUrl($this->getFieldValue('element_' . $this->iframeElement->getId() . '_url'));
        $this->iframeElement->setWidth($this->getNumber('element_' . $this->iframeElement->getId() . '_width'));
        $this->iframeElement->setHeight($this->getNumber('element_' . $this->iframeElement->getId() . '_height'));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}