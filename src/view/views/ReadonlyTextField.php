<?php

namespace Pageflow\Core\view\views;

use Pageflow\Core\view\TemplateData;

class ReadonlyTextField extends FormField {

    public function __construct(string $name, string $label, ?string $value, ?string $className, ?string $helpText = "") {
        parent::__construct($name, $value, $label, false, false, $className, $helpText);
    }

    public function getFormFieldTemplateFilename(): string {
        return "form_readonly_textfield.tpl";
    }

    public function loadFormField(TemplateData $data): void {}

    public function getFieldType(): string {
        return 'readonly-textfield';
    }

}
