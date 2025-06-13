<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class ReadonlyTextField extends FormField {

    public function __construct(string $name, string $label, ?string $value, ?string $className, ?string $helpText = null) {
        parent::__construct($name, $value, $label, false, false, $className, $helpText);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_readonly_textfield.tpl";
    }

    public function loadFormField(TemplateData $data) {}

    public function getFieldType(): string {
        return 'readonly-textfield';
    }

}
