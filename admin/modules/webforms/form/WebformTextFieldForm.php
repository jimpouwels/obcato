<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/model/WebformTextField.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformFieldForm.php";

class WebformTextFieldForm extends WebformFieldForm {

    public function __construct(WebformTextField $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textfield";
    }

}
