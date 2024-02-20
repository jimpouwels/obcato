<?php

namespace Obcato\Core;

class WebformTextAreaForm extends WebformFieldForm {

    public function __construct(WebFormTextArea $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textarea";
    }

}
