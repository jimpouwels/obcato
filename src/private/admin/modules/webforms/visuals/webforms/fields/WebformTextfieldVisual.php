<?php
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformFieldVisual.php";
require_once CMS_ROOT . "/modules/webforms/model/WebformTextField.php";

class WebformTextfieldVisual extends WebformFieldVisual {

    public function __construct(TemplateEngine $templateEngine, WebformTextField $form_field) {
        parent::__construct($templateEngine, $form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textfield.tpl";
    }

    public function loadFieldContent(Smarty_Internal_Data $data): void {}
}

?>