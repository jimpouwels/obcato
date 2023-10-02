<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "modules/webforms/visuals/webforms/fields/webform_field_visual.php";
require_once CMS_ROOT . "core/model/webform_textfield.php";

class WebFormTextAreaVisual extends WebFormFieldVisual {

    public function __construct(?WebFormTextArea $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textarea.tpl";
    }

    public function loadFieldContent(Smarty_Internal_Data $data): void {}
}

?>