<?php

namespace Obcato\Core\elements\list_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\list_element\ListElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Button;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\SingleCheckbox;
use Obcato\Core\view\views\TextField;
use const Obcato\core\ELEMENT_HOLDER_FORM_ID;

class ListElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/list_element/list_element_form.tpl";
    private ListElement $listElement;

    public function __construct(ListElement $listElement) {
        parent::__construct();
        $this->listElement = $listElement;
    }

    public function getElement(): Element {
        return $this->listElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField('element_' . $this->listElement->getId() . '_title', $this->getTextResource("list_element_editor_title"), $this->listElement->getTitle(), false, true, null);
        $addItemButton = new Button("", $this->getTextResource("list_element_editor_add_item"), "addListItem(" . $this->listElement->getId() . ",'" . ELEMENT_HOLDER_FORM_ID . "');");

        $data->assign("list_items", $this->getListItems());
        $data->assign("add_item_button", $addItemButton->render());
        $data->assign("title_field", $titleField->render());
        $data->assign("id", $this->listElement->getId());
    }

    public function includeLinkSelector(): bool
    {
        return true;
    }

    private function getListItems(): array {
        $listItems = array();
        foreach ($this->listElement->getListItems() as $listItem) {
            $listItemValues = array();
            $itemTextField = new TextField("listitem_" . $listItem->getId() . "_text", "", $listItem->getText(), false, true, null);
            $deleteField = new SingleCheckbox("listitem_" . $listItem->getId() . "_delete", "", false, false, "");

            $listItemValues['item_text_field'] = $itemTextField->render();
            $listItemValues['delete_field'] = $deleteField->render();

            $listItems[] = $listItemValues;
        }
        return $listItems;
    }

}