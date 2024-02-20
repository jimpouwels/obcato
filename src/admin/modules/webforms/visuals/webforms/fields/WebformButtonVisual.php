<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms\fields;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\webforms\model\WebformItem;

class WebformButtonVisual extends WebformItemVisual {

    public function __construct(TemplateEngine $templateEngine, WebformItem $webform_item) {
        parent::__construct($templateEngine, $webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(TemplateData $data): void {}
}