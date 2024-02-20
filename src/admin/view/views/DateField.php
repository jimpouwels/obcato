<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class DateField extends FormField {

    public function __construct(TemplateEngine $templateEngine, string $name, string $label, ?string $value, bool $mandatory, ?string $class_name) {
        parent::__construct($templateEngine, $name, $value, $label, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_date.tpl";
    }

    public function loadFormField(TemplateData $data) {}

    public function getFieldType(): string {
        return 'date';
    }

}