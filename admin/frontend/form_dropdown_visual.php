<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/form_field_visual.php';

    class FormDropDownVisual extends FormFieldVisual {
        
        private TemplateDao $_template_dao;

        public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webform_item) {
            parent::__construct($page, $article, $webform, $webform_item);
            $this->_template_dao = TemplateDao::getInstance();
        }

        public function getFormFieldTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
        }

        public function loadFormField(Smarty_Internal_Data $data): void {
        }

    }
?>