<?php

namespace Obcato\Core\admin\modules\webforms\form;

use Obcato\Core\admin\modules\webforms\model\WebformTextArea;

class WebformTextAreaForm extends WebformFieldForm {

    public function __construct(WebFormTextArea $webformTextField) {
        parent::__construct($webformTextField);
    }

    public function loadFieldFields(): void {}

    public static function supports(string $type): bool {
        return $type == "textarea";
    }

}
