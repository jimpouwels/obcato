<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/element_visual.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";
    require_once CMS_ROOT . "view/views/form_checkbox_single.php";

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
        
        public function renderElementForm(): string {
            $title_field = new TextField('element_' . $this->_list_element->getId() . '_title', $this->getTextResource("list_element_editor_title"), $this->_list_element->getTitle(), false, true, null);
            $add_item_button = new Button("", $this->getTextResource("list_element_editor_add_item"), "addListItem(" . $this->_list_element->getId() . ",'" . ELEMENT_HOLDER_FORM_ID . "');");

            $this->getTemplateEngine()->assign("list_items", $this->getListItems());
            $this->getTemplateEngine()->assign("add_item_button", $add_item_button->render());
            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign("id", $this->_list_element->getId());

            $this->getTemplateEngine()->assign("message_no_list_items", $this->getTextResource("list_element_message_no_list_items"));
            $this->getTemplateEngine()->assign("list_item_label_value", $this->getTextResource("list_element_item_label_value"));
            $this->getTemplateEngine()->assign("list_item_label_delete", $this->getTextResource("list_element_item_label_delete"));
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
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