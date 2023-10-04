<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/modules/webforms/model/WebformTextArea.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformFieldForm.php";

class WebformTextAreaForm extends WebformFieldForm {

    public function __construct(WebFormTextArea $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textarea";
    }

}
