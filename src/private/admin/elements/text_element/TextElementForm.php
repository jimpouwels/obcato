<?php

namespace Obcato\Core;

class TextElementForm extends ElementForm {

    private TextElement $textElement;

    public function __construct(TextElement $textElement) {
        parent::__construct($textElement);
        $this->textElement = $textElement;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->textElement->setTitle($this->getFieldValue('element_' . $this->textElement->getId() . '_title'));
        $this->textElement->setText($this->getFieldValue('element_' . $this->textElement->getId() . '_text'));
    }
}

?>