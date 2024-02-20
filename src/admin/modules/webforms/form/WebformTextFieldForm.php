<?php

namespace Obcato\Core\admin\modules\webforms\form;

use Obcato\Core\admin\modules\webforms\model\WebformTextField;

class WebformTextFieldForm extends WebformFieldForm {

    public function __construct(WebformTextField $webformTextField) {
        parent::__construct($webformTextField);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textfield";
    }

}
