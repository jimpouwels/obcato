<?php

namespace Pageflow\Core\elements\iframe_element;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\request_handlers\ElementForm;

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