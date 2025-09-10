<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class DateField extends FormField {

    public function __construct(string $name, string $label, ?string $value, bool $mandatory, ?string $className) {
        parent::__construct($name, $value, $label, $mandatory, false, $className);
    }

    public function getFormFieldTemplateFilename(): string {
        return "form_date.tpl";
    }

    public function loadFormField(TemplateData $data) {}

    public function getFieldType(): string {
        return 'date';
    }

}