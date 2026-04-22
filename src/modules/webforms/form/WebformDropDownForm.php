<?php

namespace Pageflow\Core\modules\webforms\form;

use Pageflow\Core\modules\webforms\model\WebformField;

class WebformDropDownForm extends WebformFieldForm {

    public function __construct(WebFormField $webform_textField) {
        parent::__construct($webform_textField);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "dropdown";
    }

}
