<?php

namespace Obcato\Core\admin\modules\webforms\form;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\modules\webforms\model\WebformItem;

abstract class WebformItemForm extends Form {

    private WebformItem $_webform_item;

    public function __construct(WebformItem $webformButton) {
        $this->_webform_item = $webformButton;
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

    protected function getWebFormItem(): WebformItem {
        return $this->_webform_item;
    }
}
