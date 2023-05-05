<?php
    defined('_ACCESS') or die;

    abstract class FormFieldVisual extends FrontendVisual {
        
        private WebFormField $_webform_field;

        public function __construct(Page $page, ?Article $article, WebFormField $webform_field) {
            parent::__construct($page, $article);
            $this->_webform_field = $webform_field;
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . '/form_field.tpl';
        }

        protected function getFormField(): WebFormField {
            return $this->_webform_field;
        }

        public function load(): void {
            $this->assign('label', $this->_webform_field->getLabel());
            $this->assign('name', $this->_webform_field->getName());
            $this->assign('mandatory', $this->_webform_field->getMandatory());
            
            $field_data = $this->getTemplateEngine()->createChildData();
            $this->loadFormField($field_data);
            
            $this->assign('form_field_html', $this->getTemplateEngine()->fetch($this->getFormFieldTemplateFilename(), $field_data));
        }

        abstract function loadFormField(Smarty_Internal_Data $data): void;
        abstract function getFormFieldTemplateFilename(): string;
    }
?>