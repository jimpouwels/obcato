<?php

namespace Pageflow\Core\elements\text_element\visuals;

use Pageflow\Core\core\model\Element;
use Pageflow\Core\elements\text_element\TextElement;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\ElementVisual;
use Pageflow\Core\view\views\Pulldown;
use Pageflow\Core\view\views\RichTextArea;
use Pageflow\Core\view\views\TextField;

class TextElementEditor extends ElementVisual {

    private static string $TEMPLATE = "text_element/templates/text_element_form.tpl";

    private TextElement $textElement;

    public function __construct(TextElement $textElement) {
        parent::__construct();
        $this->textElement = $textElement;
    }

    public function getElement(): Element {
        return $this->textElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField('element_' . $this->textElement->getId() . '_title', $this->getTextResource("text_element_editor_title"), $this->textElement->getTitle(), false, true, null);
        $textField = new RichTextArea('element_' . $this->textElement->getId() . '_text', $this->getTextResource("text_element_editor_text"), $this->textElement->getText(), false, true, "text-element-textarea");

        $data->assign("id", $this->textElement->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("text_field", $textField->render());
    }
}