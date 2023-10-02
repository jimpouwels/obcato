<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/core/model/WebformItem.php";
require_once CMS_ROOT . '/modules/webforms/form/webform_item_form.php';

abstract class WebFormFieldForm extends WebFormItemForm {

    public function __construct(WebFormField $webform_item) {
        parent::__construct($webform_item);
    }

    public function loadItemFields(): void {
        $this->getWebFormItem()->setMandatory($this->getCheckboxValue("webform_field_{$this->getWebFormItem()->getId()}_mandatory"));
        $this->loadFieldFields();
    }

    public abstract function loadFieldFields(): void;

}
