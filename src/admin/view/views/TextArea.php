<?php

namespace Obcato\Core\admin\view\views;

use Obcato\Core\admin\view\TemplateData;

class TextArea extends FormField {

    public function __construct(string $name, string $label, ?string $value, bool $mandatory, bool $linkable, ?string $className) {
        parent::__construct($name, $value, $label, $mandatory, $linkable, $className);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_textarea.tpl";
    }

    public function loadFormField(TemplateData $data) {}

    public function getFieldType(): string {
        return 'textarea';
    }

}