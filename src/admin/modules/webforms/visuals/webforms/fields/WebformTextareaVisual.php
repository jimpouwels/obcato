<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms\fields;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\webforms\model\WebformTextArea;

class WebformTextareaVisual extends WebformFieldVisual {

    public function __construct(TemplateEngine $templateEngine, ?WebFormTextArea $form_field) {
        parent::__construct($templateEngine, $form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textarea.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}
