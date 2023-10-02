<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/form_field.php";

class TextField extends FormField {

    private bool $_is_visible;

    public function __construct(string $name, ?string $label_resource_identifier, ?string $value, bool $mandatory, bool $linkable, ?string $class_name, bool $is_visible = true) {
        parent::__construct($name, $value, $label_resource_identifier, $mandatory, $linkable, $class_name);
        $this->_is_visible = $is_visible;
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_textfield.tpl";
    }

    public function loadFormField(Smarty_Internal_Data $data): void {
        $data->assign("is_visible", $this->_is_visible);
    }

    public function getFieldType(): string {
        return 'textfield';
    }

}
