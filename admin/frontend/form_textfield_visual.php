<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/form_field_visual.php';

    class FormTextFieldVisual extends FormFieldVisual {

        public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormField $webform_field) {
            parent::__construct($page, $article, $webform, $webform_field);
        }

        public function getFormFieldTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getFormItem()->getTemplate()->getFileName();
        }

        public function loadFormField(Smarty_Internal_Data $data): void {
        }

    }
?>