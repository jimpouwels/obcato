<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class TextField extends FormField {

    private bool $isVisible;
    private string $postfix;

    public function __construct(string $name, ?string $labelResourceIdentifier, ?string $value, bool $mandatory, bool $linkable, ?string $className, bool $isVisible = true, string $postfix = "") {
        parent::__construct($name, $value, $labelResourceIdentifier, $mandatory, $linkable, $className);
        $this->isVisible = $isVisible;
        $this->postfix = $postfix;
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_textfield.tpl";
    }

    public function loadFormField(TemplateData $data): void {
        $data->assign("postfix", $this->postfix);
        $data->assign("is_visible", $this->isVisible);
    }

    public function getFieldType(): string {
        return 'textfield';
    }

}
