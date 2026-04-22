<?php

namespace Pageflow\Core\elements\separator_element\visuals;

use Pageflow\Core\core\model\Element;
use Pageflow\Core\elements\separator_element\SeparatorElement;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\ElementVisual;
use Pageflow\Core\view\views\TextField;

class SeparatorElementEditor extends ElementVisual {

    private static string $TEMPLATE = "separator_element/templates/separator_element_form.tpl";
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
        $htmlIdField = new TextField("element_" . $this->separatorElement->getId() . "_html_id", $this->getTextResource("separator_element_editor_html_id"), $this->separatorElement->getHtmlId(), false, false, null);
        $data->assign("title_field", $titleField->render());
        $data->assign("html_id_field", $htmlIdField->render());
    }
}
