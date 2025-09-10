<?php

namespace Obcato\Core\elements\text_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\text_element\TextElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Pulldown;
use Obcato\Core\view\views\TextArea;
use Obcato\Core\view\views\TextField;

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
        $textField = new TextArea('element_' . $this->textElement->getId() . '_text', $this->getTextResource("text_element_editor_text"), $this->textElement->getText(), false, true, null);

        $data->assign("id", $this->textElement->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("text_field", $textField->render());
    }

    public function includeLinkSelector(): bool
    {
        return true;
    }
}