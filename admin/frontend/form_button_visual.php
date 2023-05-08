<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/form_item_visual.php';

    class FormButtonVisual extends FormItemVisual {
        

        public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webform_item) {
            parent::__construct($page, $article, $webform, $webform_item);
        }

        public function getFormItemTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getFormItem()->getTemplate()->getFileName();
        }

        public function loadFormItem(Smarty_Internal_Data $data): void {
        }

    }
?>