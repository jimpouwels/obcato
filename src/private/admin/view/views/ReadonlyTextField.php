<?php
require_once CMS_ROOT . "/view/views/ReadonlyTextField.php";

class ReadonlyTextField extends FormField {

    public function __construct(TemplateEngine $templateEngine, string $name, string $label, ?string $value, ?string $class_name) {
        parent::__construct($templateEngine, $name, $value, $label, false, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_readonly_textfield.tpl";
    }

    public function loadFormField(Smarty_Internal_Data $data) {}

    public function getFieldType(): string {
        return 'readonly-textfield';
    }

}
