<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformFieldVisual.php";
require_once CMS_ROOT . "/core/model/WebformTextField.php";

class WebFormTextFieldVisual extends WebFormFieldVisual {

    public function __construct(WebFormTextField $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textfield.tpl";
    }

    public function loadFieldContent(Smarty_Internal_Data $data): void {}
}

?>