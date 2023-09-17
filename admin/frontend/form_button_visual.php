<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/form_item_visual.php';
    require_once CMS_ROOT . 'database/dao/template_dao.php';

    class FormButtonVisual extends FormItemVisual {
        
        private TemplateDao $_template_dao;

        public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webform_item) {
            parent::__construct($page, $article, $webform, $webform_item);
            $this->_template_dao = TemplateDao::getInstance();
        }

        public function getFormItemTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
        }

        public function loadFormItem(Smarty_Internal_Data $data): void {
        }

    }
?>