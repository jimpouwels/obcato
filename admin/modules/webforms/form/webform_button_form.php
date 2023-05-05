<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/model/webform_textfield.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_item_form.php";

    class WebFormTextFieldForm extends WebFormItemForm {

        public function __construct(WebFormButton $webform_item) {
            parent::__construct($webform_item);
        }

        public function loadItemFields(WebFormItem $webform_item): void {
        }

        public static function supports(string $type): bool {
            return $type == "textfield";
        }

    }
