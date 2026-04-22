<?php

namespace Pageflow\Core\modules\webforms\form;

use Pageflow\Core\modules\webforms\model\WebformTextfield;

class WebformTextfieldForm extends WebformFieldForm {

    public function __construct(WebformTextfield $webformTextField) {
        parent::__construct($webformTextField);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textfield";
    }

}
