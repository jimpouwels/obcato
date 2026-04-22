<?php

namespace Pageflow\Core\elements\separator_element;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\request_handlers\ElementForm;

class SeparatorElementForm extends ElementForm {

    public function __construct(SeparatorElement $separatorElement) {
        parent::__construct($separatorElement);
    }

    public function loadFields(): void {
        parent::loadFields();
        $elementId = $this->getElement()->getId();
        $title = $this->getFieldValue('element_' . $elementId . '_title');
        $htmlId = $this->getFieldValue('element_' . $elementId . '_html_id');

        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            $this->getElement()->setTitle($title);
            $this->getElement()->setHtmlId($htmlId);
        }
    }

}