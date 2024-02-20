<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class WebformButtonVisual extends WebformItemVisual {

    public function __construct(TemplateEngine $templateEngine, WebFormItem $webform_item) {
        parent::__construct($templateEngine, $webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(TemplateData $data): void {}
}