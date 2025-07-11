<?php

namespace Obcato\Core\elements\separator_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\separator_element\SeparatorElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\TextField;

class SeparatorElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/separator_element/separator_element_form.tpl";
    private SeparatorElement $separatorElement;

    public function __construct(SeparatorElement $separatorElement) {
        parent::__construct();
        $this->separatorElement = $separatorElement;
    }

    public function getElement(): Element {
        return $this->separatorElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField("element_" . $this->separatorElement->getId() . "_title", $this->getTextResource("separator_element_editor_title"), $this->separatorElement->getTitle(), false, true, null);
        $data->assign("title_field", $titleField->render());
    }

    public function includeLinkSelector(): bool
    {
        return false;
    }

}
