<?php

namespace Obcato\Core\modules\webforms\visuals\webforms\fields;

use Obcato\Core\modules\webforms\model\WebformItem;
use Obcato\Core\view\TemplateData;

class WebformButtonVisual extends WebformItemVisual {

    public function __construct(WebformItem $webform_item) {
        parent::__construct($webform_item);
    }

    public function getFormItemTemplate(): string {
        return "webforms/templates/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(TemplateData $data): void {}
}