<?php

namespace Obcato\Core;

class WebformTextFieldForm extends WebformFieldForm {

    public function __construct(WebformTextField $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textfield";
    }

}
