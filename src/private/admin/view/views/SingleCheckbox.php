<?php
require_once CMS_ROOT . "/view/views/FormField.php";

class SingleCheckbox extends FormField {

    private string $_onchange_js = '';

    public function __construct(TemplateEngine $templateEngine, string $name, string $label_identifier, string $value, bool $mandatory, ?string $class_name) {
        parent::__construct($templateEngine, $name, $value, $label_identifier, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_checkbox_single.tpl";
    }

    public function loadFormField(Smarty_Internal_Data $data) {
        $data->assign('onchange_js', "onChange={$this->_onchange_js}");
    }

    public function setOnChangeJS(string $onchange_js): void {
        $this->_onchange_js = $onchange_js;
    }

    public function getFieldType(): string {
        return 'checkbox';
    }

}