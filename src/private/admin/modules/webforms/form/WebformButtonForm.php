<?php

namespace Obcato\Core;

class WebformButtonForm extends WebformItemForm {

    public function __construct(WebFormButton $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadItemFields(): void {}

    public static function supports(string $type): bool {
        return $type == "button";
    }

}
