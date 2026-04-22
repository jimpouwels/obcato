<?php

namespace Pageflow\Core\elements\list_element\visuals;

use Pageflow\Core\core\model\Element;
use Pageflow\Core\elements\list_element\ListElement;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Button;
use Pageflow\Core\view\views\ElementVisual;
use Pageflow\Core\view\views\SingleCheckbox;
use Pageflow\Core\view\views\RichTextArea;
use Pageflow\Core\view\views\TextField;
use const Pageflow\Core\ELEMENT_HOLDER_FORM_ID;

class ListElementEditor extends ElementVisual {

    private static string $TEMPLATE = "list_element/templates/list_element_form.tpl";
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
        $data->assign("list_item_order", $this->getListItemOrder());
        $data->assign("add_item_button", $addItemButton->render());
        $data->assign("title_field", $titleField->render());
        $data->assign("id", $this->listElement->getId());
    }

    private function getListItems(): array {
        $listItems = array();
        foreach ($this->listElement->getListItems() as $listItem) {
            $listItemValues = array();
            $itemTextField = new RichTextArea("listitem_" . $listItem->getId() . "_text", "", $listItem->getText(), false, true, "list-item-textarea list-item-rich-text");
            $deleteField = new SingleCheckbox("listitem_" . $listItem->getId() . "_delete", "", false, false, "");

            $listItemValues['item_text_field'] = $itemTextField->render();
            $listItemValues['delete_field'] = $deleteField->render();
            $listItemValues['id'] = $listItem->getId();

            $listItems[] = $listItemValues;
        }
        return $listItems;
    }

    private function getListItemOrder(): string {
        $items = array_filter($this->listElement->getListItems(), fn($item) => $item->getId());
        return implode(',', array_map(fn($item) => $item->getId(), $items));
    }

}