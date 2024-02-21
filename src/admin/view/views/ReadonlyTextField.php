<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class ReadonlyTextField extends FormField {

    public function __construct(TemplateEngine $templateEngine, string $name, string $label, ?string $value, ?string $class_name) {
        parent::__construct($templateEngine, $name, $value, $label, false, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_readonly_textfield.tpl";
    }

    public function loadFormField(TemplateData $data) {}

    public function getFieldType(): string {
        return 'readonly-textfield';
    }

}
