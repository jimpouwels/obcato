<?php

namespace Pageflow\Core\request_handlers;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\core\model\Element;

abstract class ElementForm extends Form {

    private Element $element;

    public function __construct(Element $element) {
        $this->element = $element;
    }

    protected function getElement(): ?Element {
        return $this->element;
    }

    public function loadFields(): void {
        $templateIdString = $this->getFieldValue('element_' . $this->element->getId() . '_template');
        $templateId = null;
        if (!empty($templateIdString)) {
            $templateId = intval($templateIdString);
        }
        $includeInTableOfContents = $this->getCheckboxValue('element_' . $this->element->getId() . '_toc');
        $this->element->setTemplateId($templateId);
        $this->element->setIncludeInTableOfContents($includeInTableOfContents == 1);
    }
}