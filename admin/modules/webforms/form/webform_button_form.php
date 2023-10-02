<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/model/WebformTextField.php";
require_once CMS_ROOT . "/modules/webforms/form/webform_item_form.php";

class WebFormButtonForm extends WebFormItemForm {

    public function __construct(WebFormButton $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadItemFields(): void {}

    public static function supports(string $type): bool {
        return $type == "button";
    }

}
