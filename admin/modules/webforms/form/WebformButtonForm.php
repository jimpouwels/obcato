<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/modules/webforms/model/WebformTextField.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformItemForm.php";

class WebformButtonForm extends WebformItemForm {

    public function __construct(WebFormButton $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadItemFields(): void {}

    public static function supports(string $type): bool {
        return $type == "button";
    }

}
