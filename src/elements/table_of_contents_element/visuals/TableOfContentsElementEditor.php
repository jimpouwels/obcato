<?php

namespace Pageflow\Core\elements\table_of_contents_element\visuals;

use Pageflow\Core\core\model\Element;
use Pageflow\Core\elements\table_of_contents_element\TableOfContentsElement;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\ElementVisual;
use Pageflow\Core\view\views\TextField;

class TableOfContentsElementEditor extends ElementVisual {

    private static string $TEMPLATE = "table_of_contents_element/templates/table_of_contents_element_form.tpl";
    private TableOfContentsElement $element;

    public function __construct(TableOfContentsElement $element) {
        parent::__construct();
        $this->element = $element;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function getElement(): Element {
        return $this->element;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField("element_" . $this->element->getId() . "_title", "Titel", $this->element->getTitle(), false, true, null);
        $data->assign("title_field", $titleField->render());
    }
}