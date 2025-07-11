<?php

namespace Obcato\Core\elements\separator_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\request_handlers\ElementForm;

class SeparatorElementForm extends ElementForm {

    public function __construct(SeparatorElement $separatorElement) {
        parent::__construct($separatorElement);
    }

    public function loadFields(): void {
        parent::loadFields();
        $elementId = $this->getElement()->getId();
        $title = $this->getFieldValue('element_' . $elementId . '_title');

        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            $this->getElement()->setTitle($title);
        }
    }

}