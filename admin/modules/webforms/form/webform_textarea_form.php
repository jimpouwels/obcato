<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/model/WebformTextArea.php";
require_once CMS_ROOT . "/modules/webforms/form/webform_field_form.php";

class WebFormTextAreaForm extends WebFormFieldForm {

    public function __construct(WebFormTextArea $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textarea";
    }

}
