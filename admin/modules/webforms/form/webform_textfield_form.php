<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/model/webform_textfield.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_field_form.php";

    class WebFormTextFieldForm extends WebFormFieldForm {

        public function __construct(WebFormTextField $webform_field) {
            parent::__construct($webform_field);
        }

        public function loadCustomFields(WebFormField $webform_field): void {
        }

        public static function supports(string $type): bool {
            return $type == "textfield";
        }

    }
