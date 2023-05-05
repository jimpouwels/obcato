<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/form_field_visual.php';

    class FormDropDownVisual extends FormFieldVisual {
        
        private WebFormField $_webform_field;

        public function __construct(Page $page, ?Article $article, WebFormField $webform_field) {
            parent::__construct($page, $article, $webform_field);
        }

        public function getFormFieldTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getFormField()->getTemplate()->getFileName();
        }

        public function loadFormField(Smarty_Internal_Data $data): void {
        }

    }
?>