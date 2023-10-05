<?php
require_once CMS_ROOT . "/view/views/FormField.php";

class UploadField extends FormField {

    public function __construct(string $name, string $label, bool $mandatory, ?string $class_name) {
        parent::__construct($name, null, $label, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_upload_field.tpl";
    }

    public function loadFormField(Smarty_Internal_Data $data): void {}

    public function getFieldType(): string {
        return 'upload';
    }

}