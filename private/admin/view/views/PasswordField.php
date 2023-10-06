<?php
require_once CMS_ROOT . "/view/views/TextField.php";

class PasswordField extends FormField {

    public function __construct(string $name, string $label, string $value, bool $mandatory, ?string $class_name) {
        parent::__construct($name, $value, $label, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_password.tpl";
    }

    public function loadFormField(Smarty_Internal_Data $data) {}

    public function getFieldType(): string {
        return 'password';
    }

}