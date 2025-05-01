<?php

namespace Obcato\Core\request_handlers;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\model\Element;

abstract class ElementForm extends Form {

    private Element $element;

    public function __construct(Element $element) {
        $this->element = $element;
    }

    public function loadFields(): void {
        $templateIdString = $this->getFieldValue('element_' . $this->element->getId() . '_template');
        $templateId = null;
        if (!empty($templateIdString)) {
            $templateId = intval($templateIdString);
        }
        $includeInTableOfContents = $this->getCheckboxValue('element_' . $this->element->getId() . '_toc');
        $this->element->setTemplateId($templateId);
        $this->element->setIncludeInTableOfContents($includeInTableOfContents == 1 ? true : false);
    }
}