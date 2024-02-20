<?php

namespace Obcato\Core;

abstract class WebformFieldForm extends WebformItemForm {

    public function __construct(WebFormField $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadItemFields(): void {
        $this->getWebFormItem()->setMandatory($this->getCheckboxValue("webform_field_{$this->getWebFormItem()->getId()}_mandatory"));
        $this->loadFieldFields();
    }

    public abstract function loadFieldFields(): void;

}
