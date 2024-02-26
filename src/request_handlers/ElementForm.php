<?php

namespace Obcato\Core\request_handlers;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\model\Element;

abstract class ElementForm extends Form {

    private Element $_element;

    public function __construct(Element $element) {
        $this->_element = $element;
    }

    public function loadFields(): void {
        $template_id_string_val = $this->getFieldValue('element_' . $this->_element->getId() . '_template');
        $template_id = NULL;
        if (!empty($template_id_string_val)) {
            $template_id = intval($template_id_string_val);
        }
        $include_in_toc = $this->getCheckboxValue('element_' . $this->_element->getId() . '_toc');
        $this->_element->setTemplateId($template_id);
        $this->_element->setIncludeInTableOfContents($include_in_toc == 1 ? true : false);
    }
}