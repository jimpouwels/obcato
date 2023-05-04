<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "core/model/webform_field.php";

    abstract class WebFormFieldVisual extends Visual {

        private WebFormField $_webform_field;

        public function __construct(WebFormField $webform_field) {
            parent::__construct();
            $this->_webform_field = $webform_field;
        }

        public function getTemplateFilename(): string {
            return "modules/webforms/webforms/fields/webform_formfield.tpl";
        }
        
        abstract function getFormFieldTemplate(): string;

        abstract function loadFieldContent(Smarty_Internal_Data $data): void;

        public function load(): void {
            $form_field_content_template_data = $this->getTemplateEngine()->createChildData($this->getParentTemplateData());
            $this->loadFieldContent($form_field_content_template_data);
            
            $this->assign('id', $this->_webform_field->getId());
            $this->assign('type', $this->_webform_field->getType());
            $label_field = new TextField("webform_field_{$this->_webform_field->getId()}_label", "webforms_editor_field_label_label", $this->_webform_field->getLabel(), true, false, null);
            $name_field = new TextField("webform_field_{$this->_webform_field->getId()}_name", "webforms_editor_field_name_label", $this->_webform_field->getName(), true, false, null);
            $this->assign("name_field", $name_field->render());
            $this->assign("label_field", $label_field->render());
            $this->assign('custom_editor', $this->getTemplateEngine()->fetch($this->getFormFieldTemplate(), $form_field_content_template_data));
        }

    }
?>