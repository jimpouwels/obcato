<?php
require_once CMS_ROOT . "/modules/webforms/model/WebformField.php";

abstract class WebformItemVisual extends Visual {

    private WebFormItem $_webform_item;

    public function __construct(WebFormItem $webform_item) {
        parent::__construct();
        $this->_webform_item = $webform_item;
    }

    public function getTemplateFilename(): string {
        return "modules/webforms/webforms/fields/webform_item.tpl";
    }

    protected function getWebFormItem(): WebFormItem {
        return $this->_webform_item;
    }

    abstract function getFormItemTemplate(): string;

    abstract function loadItemContent(Smarty_Internal_Data $data): void;

    public function load(): void {
        $form_item_content_template_data = $this->getTemplateEngine()->createChildData();
        $this->loadItemContent($form_item_content_template_data);

        $template_picker = new TemplatePicker("webform_item_{$this->_webform_item->getId()}_template", "", false, "template_picker", $this->_webform_item->getTemplate(), $this->_webform_item->getScope());
        $this->assign('template_picker', $template_picker->render());

        $this->assign('id', $this->_webform_item->getId());
        $this->assign('type', $this->_webform_item->getType());
        $this->assign('index', 0);
        $label_field = new TextField("webform_item_{$this->_webform_item->getId()}_label", "webforms_editor_field_label_label", $this->_webform_item->getLabel(), true, false, null);
        $name_field = new TextField("webform_item_{$this->_webform_item->getId()}_name", "webforms_editor_field_name_label", $this->_webform_item->getName(), true, false, null);
        $this->assign("name_field", $name_field->render());
        $this->assign("label_field", $label_field->render());
        $this->assign('item_editor', $this->getTemplateEngine()->fetch($this->getFormItemTemplate(), $form_item_content_template_data));
    }

}

?>