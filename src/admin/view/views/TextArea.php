<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TextArea extends FormField {

    public function __construct(TemplateEngine $templateEngine, string $name, string $label, ?string $value, bool $mandatory, bool $linkable, ?string $className) {
        parent::__construct($templateEngine, $name, $value, $label, $mandatory, $linkable, $className);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_textarea.tpl";
    }

    public function loadFormField(TemplateData $data) {}

    public function getFieldType(): string {
        return 'textarea';
    }

}