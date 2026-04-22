<?php

namespace Pageflow\Core\modules\webforms\visuals\webforms\fields;

use Pageflow\Core\modules\webforms\model\WebformTextfield;
use Pageflow\Core\view\TemplateData;

class WebformTextfieldVisual extends WebformFieldVisual {

    public function __construct(WebformTextfield $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "webforms/templates/webforms/fields/webform_textfield.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}