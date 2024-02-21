<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms\fields;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\webforms\model\WebformTextfield;

class WebformTextfieldVisual extends WebformFieldVisual {

    public function __construct(TemplateEngine $templateEngine, WebformTextfield $form_field) {
        parent::__construct($templateEngine, $form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textfield.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}