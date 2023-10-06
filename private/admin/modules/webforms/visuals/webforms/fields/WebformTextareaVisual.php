<?php
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformFieldVisual.php";
require_once CMS_ROOT . "/modules/webforms/model/WebformTextField.php";

class WebformTextareaVisual extends WebformFieldVisual {

    public function __construct(?WebFormTextArea $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textarea.tpl";
    }

    public function loadFieldContent(Smarty_Internal_Data $data): void {}
}

?>