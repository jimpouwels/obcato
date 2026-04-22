<?php

namespace Pageflow\Core\modules\webforms\form;

use Pageflow\Core\modules\webforms\model\WebformButton;

class WebformButtonForm extends WebformItemForm {

    public function __construct(WebformButton $webformButton) {
        parent::__construct($webformButton);
    }

    public function loadItemFields(): void {}

    public static function supports(string $type): bool {
        return $type == "button";
    }

}
