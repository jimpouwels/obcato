<?php
require_once CMS_ROOT . "/modules/webforms/model/WebformField.php";
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformItemVisual.php";

abstract class WebformFieldVisual extends WebformItemVisual {

    public function __construct(TemplateEngine $templateEngine, WebFormItem $webform_item) {
        parent::__construct($templateEngine, $webform_item);
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

        $mandatory_field = new SingleCheckbox($this->getTemplateEngine(), "webform_field_{$this->getWebFormItem()->getId()}_mandatory", 'webforms_editor_field_mandatory_label', $this->getWebFormItem()->getMandatory(), false, null);
        $data->assign('mandatory_field', $mandatory_field->render());
    }

}

?>