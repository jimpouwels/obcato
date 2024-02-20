<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class UploadField extends FormField {

    public function __construct(TemplateEngine $templateEngine, string $name, string $label, bool $mandatory, ?string $class_name) {
        parent::__construct($templateEngine, $name, null, $label, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_upload_field.tpl";
    }

    public function loadFormField(TemplateData $data): void {}

    public function getFieldType(): string {
        return 'upload';
    }

}