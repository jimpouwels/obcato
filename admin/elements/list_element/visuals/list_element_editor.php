<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/SingleCheckbox.php";

class ListElementEditorVisual extends ElementVisual {

    private static string $TEMPLATE = "elements/list_element/list_element_form.tpl";
    private ListElement $_list_element;

    public function __construct(ListElement $list_element) {
        parent::__construct();
        $this->_list_element = $list_element;
    }

    public function getElement(): Element {
        return $this->_list_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField('element_' . $this->_list_element->getId() . '_title', $this->getTextResource("list_element_editor_title"), $this->_list_element->getTitle(), false, true, null);
        $add_item_button = new Button("", $this->getTextResource("list_element_editor_add_item"), "addListItem(" . $this->_list_element->getId() . ",'" . ELEMENT_HOLDER_FORM_ID . "');");

        $data->assign("list_items", $this->getListItems());
        $data->assign("add_item_button", $add_item_button->render());
        $data->assign("title_field", $title_field->render());
        $data->assign("id", $this->_list_element->getId());

        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

    private function getListItems(): array {
        $list_items = array();
        foreach ($this->_list_element->getListItems() as $list_item) {
            $list_item_values = array();
            $item_text_field = new TextField("listitem_" . $list_item->getId() . "_text", "", $list_item->getText(), false, true, null);
            $delete_field = new SingleCheckbox("listitem_" . $list_item->getId() . "_delete", "", false, false, "");

            $list_item_values['item_text_field'] = $item_text_field->render();
            $list_item_values['delete_field'] = $delete_field->render();

            $list_items[] = $list_item_values;
        }
        return $list_items;
    }

}

?>