<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/model/WebformDropdown.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformFieldForm.php";

class WebformDropDownForm extends WebformFieldForm {

    public function __construct(WebFormField $webform_field) {
        parent::__construct($webform_field);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "dropdown";
    }

}
