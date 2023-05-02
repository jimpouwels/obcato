<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";

    class WebFormEditor extends Panel {

        private WebForm $_current_webform;

        public function __construct(?WebForm $current_webform) {
            parent::__construct("webforms_editor_panel_title");
            $this->_current_webform = $current_webform;
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $title_text_field = new TextField("title", "webforms_editor_title_field", $this->_current_webform->getTitle(), true, false, null);
            $data->assign("action_form_id", ACTION_FORM_ID);
            $data->assign("id", $this->_current_webform->getId());
            $data->assign("title_field", $title_text_field->render());
        }

    }
?>