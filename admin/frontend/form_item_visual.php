<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/frontend_visual.php';

    abstract class FormItemVisual extends FrontendVisual {
        
        private WebFormItem $_webform_item;

        public function __construct(Page $page, ?Article $article, WebFormItem $webform_item) {
            parent::__construct($page, $article);
            $this->_webform_item = $webform_item;
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . '/form_item.tpl';
        }

        protected function getFormItem(): WebFormItem {
            return $this->_webform_item;
        }

        public function load(): void {
            $this->assign('label', $this->getFormItem()->getLabel());
            $this->assign('name', $this->getFormItem()->getName());
            
            $field_data = $this->createChildData(true);
            $this->loadFormItem($field_data);
            $this->assign('form_item_html', $this->getTemplateEngine()->fetch($this->getFormItemTemplateFilename(), $field_data));
        }

        abstract function loadFormItem(Smarty_Internal_Data $data): void;
        abstract function getFormItemTemplateFilename(): string;
    }
?>