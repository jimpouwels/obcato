<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/form_field_visual.php';

    class FormTextAreaVisual extends FormFieldVisual {
        
        public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webform_item) {
            parent::__construct($page, $article, $webform, $webform_item);
        }

        public function getFormFieldTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getFormItem()->getTemplate()->getFileName();
        }

        public function loadFormField(Smarty_Internal_Data $data): void {
        }

    }
?>