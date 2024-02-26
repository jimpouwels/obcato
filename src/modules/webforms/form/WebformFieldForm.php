<?php

namespace Obcato\Core\modules\webforms\form;

use Obcato\Core\modules\webforms\model\WebformField;

abstract class WebformFieldForm extends WebformItemForm {

    public function __construct(WebFormField $webformTextField) {
        parent::__construct($webformTextField);
    }

    public function loadItemFields(): void {
        $this->getWebFormItem()->setMandatory($this->getCheckboxValue("webform_field_{$this->getWebFormItem()->getId()}_mandatory"));
        $this->loadFieldFields();
    }

    public abstract function loadFieldFields(): void;

}
