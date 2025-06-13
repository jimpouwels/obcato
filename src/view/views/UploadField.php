<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class UploadField extends FormField {

    public function __construct(string $name, string $label, bool $mandatory, ?string $className) {
        parent::__construct($name, null, $label, $mandatory, false, $className);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_upload_field.tpl";
    }

    public function loadFormField(TemplateData $data): void {}

    public function getFieldType(): string {
        return 'upload';
    }

}