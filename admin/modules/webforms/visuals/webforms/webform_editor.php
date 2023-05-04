<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";
    require_once CMS_ROOT . "view/views/button.php";
    require_once CMS_ROOT . "database/dao/webform_dao.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/fields/webform_textfield_visual.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/fields/webform_textarea_visual.php";

    class WebFormEditor extends Panel {

        private WebForm $_current_webform;
        private WebFormDao $_webform_dao;

        public function __construct(?WebForm $current_webform) {
            parent::__construct("webforms_webform_editor_panel_title");
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_current_webform = $current_webform;
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/webform_editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $add_textfield_button = new Button("", "webforms_add_textfield_button_label", "addFormField('textfield');");
            $data->assign("button_add_textfield", $add_textfield_button->render());

            $add_textarea_button = new Button("", "webforms_add_textarea_button_label", "addFormField('textarea');");
            $data->assign("button_add_textarea", $add_textarea_button->render());

            $data->assign("form_fields", $this->renderFormFields());            
        }
        
        private function renderFormFields(): array {
            $form_fields_data = array();
            $form_fields = $this->_webform_dao->getFormFieldsByWebForm($this->_current_webform->getId());
            foreach ($form_fields as $form_field) {
                $field = null;
                if ($form_field instanceof WebFormTextField) {
                    $field = new WebFormTextFieldVisual($form_field);
                } else if ($form_field instanceof WebFormTextArea) {
                    $field = new WebFormTextAreaVisual($form_field);
                } else if ($form_field instanceof WebFormDropDown) {
                    $field = new WebFormDropDown($form_field);
                }
                $form_fields_data[] = $field->render();
            }
            return $form_fields_data;
        }

    }
?>