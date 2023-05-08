<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";
    require_once CMS_ROOT . "view/views/form_checkbox_single.php";

    class WebFormMetadataEditor extends Panel {

        private WebForm $_current_webform;

        public function __construct(WebForm $current_webform, ?Visual $parent = null) {
            parent::__construct("webforms_metdata_editor_panel_title", "", $parent);
            $this->_current_webform = $current_webform;
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/metadata_editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $title_text_field = new TextField("title", "webforms_editor_title_field", $this->_current_webform->getTitle(), true, false, null);
            $data->assign("title_field", $title_text_field->render());
            
            $captcha_checkbox = new SingleCheckbox('include_captcha', 'webforms_editor_captcha_field', $this->_current_webform->getIncludeCaptcha(), false, null);
            $data->assign("include_captcha_field", $captcha_checkbox->render());
        }

    }
?>