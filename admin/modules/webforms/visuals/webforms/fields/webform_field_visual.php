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
            $this->assign('name', $this->_webform_field->getName());
            $this->assign('label', $this->_webform_field->getLabel());
            $this->assign('mandatory', $this->_webform_field->getMandatory());
            $this->assign('custom_editor', $this->getTemplateEngine()->fetch($this->getFormFieldTemplate(), $form_field_content_template_data));
        }

    }
?>