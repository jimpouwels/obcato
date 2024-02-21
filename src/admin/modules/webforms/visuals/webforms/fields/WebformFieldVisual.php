<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms\fields;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\modules\webforms\model\WebformItem;
use Obcato\Core\admin\view\views\SingleCheckbox;

abstract class WebformFieldVisual extends WebformItemVisual {

    public function __construct(WebformItem $webform_item) {
        parent::__construct($webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_field.tpl";
    }

    abstract function getFormFieldTemplate(): string;

    abstract function loadFieldContent(TemplateData $data): void;

    public function loadItemContent(TemplateData $data): void {
        $form_field_content_template_data = $this->createChildData();
        $this->loadFieldContent($form_field_content_template_data);
        $data->assign('field_editor', $this->getTemplateEngine()->fetch($this->getFormFieldTemplate(), $form_field_content_template_data));

        $mandatory_field = new SingleCheckbox("webform_field_{$this->getWebFormItem()->getId()}_mandatory", 'webforms_editor_field_mandatory_label', $this->getWebFormItem()->getMandatory(), false, null);
        $data->assign('mandatory_field', $mandatory_field->render());
    }

}