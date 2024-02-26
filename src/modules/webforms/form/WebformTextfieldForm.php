<?php

namespace Obcato\Core\modules\webforms\form;

use Obcato\Core\modules\webforms\model\WebformTextfield;

class WebformTextfieldForm extends WebformFieldForm {

    public function __construct(WebformTextfield $webformTextField) {
        parent::__construct($webformTextField);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textfield";
    }

}
