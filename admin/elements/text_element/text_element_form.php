<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/element_form.php";

class TextElementForm extends ElementForm {

    private TextElement $_text_element;

    public function __construct(TextElement $text_element) {
        parent::__construct($text_element);
        $this->_text_element = $text_element;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->_text_element->setTitle($this->getFieldValue('element_' . $this->_text_element->getId() . '_title'));
        $this->_text_element->setText($this->getFieldValue('element_' . $this->_text_element->getId() . '_text'));
    }
}

?>