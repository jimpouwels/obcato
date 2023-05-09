<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";
    require_once CMS_ROOT . "view/views/button.php";
    require_once CMS_ROOT . "database/dao/webform_dao.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/fields/webform_textfield_visual.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/fields/webform_textarea_visual.php";
    require_once CMS_ROOT . "modules/webforms/webform_item_factory.php";

    class WebFormEditor extends Panel {

        private WebForm $_current_webform;
        private WebFormDao $_webform_dao;
        private WebFormItemFactory $_webform_item_factory;

        public function __construct(?WebForm $current_webform) {
            parent::__construct("webforms_webform_editor_panel_title");
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
            $this->_current_webform = $current_webform;
        }

        public function getPanelContentTemplate(): string {
            return 'modules/webforms/webforms/webform_editor.tpl';
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {            
            $add_textfield_button = new Button("", "webforms_add_textfield_button_label", "addFormField('textfield');");
            $data->assign("button_add_textfield", $add_textfield_button->render());

            $add_textarea_button = new Button("", "webforms_add_textarea_button_label", "addFormField('textarea');");
            $data->assign("button_add_textarea", $add_textarea_button->render());

            $add_button_button = new Button("", "webforms_add_button_button_label", "addFormField('button');");
            $data->assign("button_add_button", $add_button_button->render());

            $data->assign("form_fields", $this->renderFormFields());            
        }
        
        private function renderFormFields(): array {
            $form_fields_data = array();
            $form_fields = $this->_webform_dao->getWebFormItemsByWebForm($this->_current_webform->getId());
            foreach ($form_fields as $form_field) {
                $form_field_data = $this->_webform_item_factory->getBackendVisualFor($form_field);
                $form_fields_data[] = $form_field_data->render();
            }
            return $form_fields_data;
        }

    }
?>