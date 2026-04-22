<?php

namespace Pageflow\Core\view\views;

use Pageflow\Core\view\TemplateData;

class SingleCheckbox extends FormField {

    private string $onChangeJs = '';

    public function __construct(string $name, string $labelIdentifier, string $value, bool $mandatory, ?string $className , ?string $helpTextResourceIdentifier = "") {
        parent::__construct($name, $value, $labelIdentifier, $mandatory, false, $className, $helpTextResourceIdentifier);
    }

    public function getFormFieldTemplateFilename(): string {
        return "form_checkbox_single.tpl";
    }

    public function loadFormField(TemplateData $data): void {
        $data->assign('onchange_js', "onChange={$this->onChangeJs}");
    }

    public function setOnChangeJS(string $onChangeJs): void {
        $this->onChangeJs = $onChangeJs;
    }

    public function getFieldType(): string {
        return 'checkbox';
    }

}