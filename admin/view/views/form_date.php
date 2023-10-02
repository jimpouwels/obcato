<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/form_textfield.php";

class DateField extends FormField {

    public function __construct(string $name, string $label, ?string $value, bool $mandatory, ?string $class_name) {
        parent::__construct($name, $value, $label, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_date.tpl";
    }

    public function loadFormField(Smarty_Internal_Data $data) {}

    public function getFieldType(): string {
        return 'date';
    }

}