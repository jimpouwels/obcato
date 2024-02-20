<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TextElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/text_element/text_element_form.tpl";

    private TextElement $textElement;

    public function __construct(TemplateEngine $templateEngine, TextElement $textElement) {
        parent::__construct($templateEngine);
        $this->textElement = $textElement;
    }

    public function getElement(): Element {
        return $this->textElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->getTemplateEngine(), 'element_' . $this->textElement->getId() . '_title', $this->getTextResource("text_element_editor_title"), $this->textElement->getTitle(), false, true, null);
        $textField = new TextArea($this->getTemplateEngine(), 'element_' . $this->textElement->getId() . '_text', $this->getTextResource("text_element_editor_text"), $this->textElement->getText(), false, true, null);

        $data->assign("title_field", $titleField->render());
        $data->assign("text_field", $textField->render());
    }

}