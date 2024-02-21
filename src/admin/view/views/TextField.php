<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TextField extends FormField {

    private bool $isVisible;
    private string $postfix;

    public function __construct(TemplateEngine $templateEngine, string $name, ?string $labelResourceIdentifier, ?string $value, bool $mandatory, bool $linkable, ?string $className, bool $isVisible = true, string $postfix = "") {
        parent::__construct($templateEngine, $name, $value, $labelResourceIdentifier, $mandatory, $linkable, $className);
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
