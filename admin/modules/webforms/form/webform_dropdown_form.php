<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/model/webform_dropdown.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_field_form.php";

    class WebFormDropDownForm extends WebFormFieldForm {

        public function loadCustomFields(WebFormField $webform_field): void {
        }

        public static function supports(string $type): bool {
            return $type == "dropdown";
        }

    }
