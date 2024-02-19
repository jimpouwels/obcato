<?php
require_once CMS_ROOT . "/view/views/TextField.php";

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