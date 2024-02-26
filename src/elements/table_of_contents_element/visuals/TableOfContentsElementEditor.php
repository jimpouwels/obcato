<?php

namespace Obcato\Core\elements\table_of_contents_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\table_of_contents_element\TableOfContentsElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\TextField;

class TableOfContentsElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/table_of_contents_element/table_of_contents_element_form.tpl";
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