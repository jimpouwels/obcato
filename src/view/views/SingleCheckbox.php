<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class SingleCheckbox extends FormField {

    private string $_onchange_js = '';

    public function __construct(string $name, string $label_identifier, string $value, bool $mandatory, ?string $class_name) {
        parent::__construct($name, $value, $label_identifier, $mandatory, false, $class_name);
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_checkbox_single.tpl";
    }

    public function loadFormField(TemplateData $data) {
        $data->assign('onchange_js', "onChange={$this->_onchange_js}");
    }

    public function setOnChangeJS(string $onchange_js): void {
        $this->_onchange_js = $onchange_js;
    }

    public function getFieldType(): string {
        return 'checkbox';
    }

}