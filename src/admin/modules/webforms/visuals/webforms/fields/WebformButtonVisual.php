<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms\fields;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\modules\webforms\model\WebformItem;

class WebformButtonVisual extends WebformItemVisual {

    public function __construct(WebformItem $webform_item) {
        parent::__construct($webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(TemplateData $data): void {}
}