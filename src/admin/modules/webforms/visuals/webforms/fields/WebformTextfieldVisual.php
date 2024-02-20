<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class WebformTextfieldVisual extends WebformFieldVisual {

    public function __construct(TemplateEngine $templateEngine, WebformTextField $form_field) {
        parent::__construct($templateEngine, $form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textfield.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}