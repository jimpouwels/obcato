<?php

namespace Obcato\Core\modules\webforms\visuals\webforms\fields;

use Obcato\Core\modules\webforms\model\WebformTextfield;
use Obcato\Core\view\TemplateData;

class WebformTextfieldVisual extends WebformFieldVisual {

    public function __construct(WebformTextfield $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textfield.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}