<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/core/model/WebformItem.php";

abstract class WebFormItemForm extends Form {

    private WebFormItem $_webform_item;

    public function __construct(WebFormItem $webform_item) {
        $this->_webform_item = $webform_item;
    }

    public function loadFields(): void {
        $this->_webform_item->setLabel($this->getMandatoryFieldValue("webform_item_{$this->_webform_item->getId()}_label"));
        $this->_webform_item->setName($this->getMandatoryFieldValue("webform_item_{$this->_webform_item->getId()}_name"));

        $template_id_string_val = $this->getFieldValue("webform_item_{$this->_webform_item->getId()}_template");
        $template_id = null;
        if (!empty($template_id_string_val)) {
            $template_id = intval($template_id_string_val);
        }
        $this->_webform_item->setTemplateId($template_id);

        $this->loadItemFields();
    }

    public abstract function loadItemFields(): void;

    protected function getWebFormItem(): WebFormItem {
        return $this->_webform_item;
    }
}
