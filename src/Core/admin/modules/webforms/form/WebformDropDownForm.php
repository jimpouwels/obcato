<?php

namespace Obcato\Core;

class WebformDropDownForm extends WebformFieldForm {

    public function __construct(WebFormField $webform_field) {
        parent::__construct($webform_field);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "dropdown";
    }

}
