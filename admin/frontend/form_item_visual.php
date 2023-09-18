<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/frontend_visual.php';
    require_once CMS_ROOT . 'frontend/handlers/form_status.php';

    abstract class FormItemVisual extends FrontendVisual {
        
        private WebFormItem $_webform_item;
        private WebForm $_webform;

        public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webform_item) {
            parent::__construct($page, $article);
            $this->_webform_item = $webform_item;
            $this->_webform = $webform;
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . '/form_item.tpl';
        }

        protected function getFormItem(): WebFormItem {
            return $this->_webform_item;
        }

        protected function getWebForm(): WebForm {
            return $this->_webform;
        }

        public function loadVisual(Smarty_Internal_Data $data, ?array &$parent_data): void {
            $this->assign('label', $this->getFormItem()->getLabel());
            $this->assign('name', $this->getFormItem()->getName());
            $this->assign('value', FormStatus::getFieldValue($this->getFormItem()->getName()));
            $this->assign('has_error', FormStatus::getError($this->getFormItem()->getName()) != null);
            
            $field_data = $this->createChildData(true);
            $this->loadFormItem($field_data);
            $this->assign('form_item_html', $this->getTemplateEngine()->fetch($this->getFormItemTemplateFilename(), $field_data));
        }

        public function getPresentable(): ?Presentable {
            return $this->_webform_item;
        }

        abstract function loadFormItem(Smarty_Internal_Data $data): void;
        abstract function getFormItemTemplateFilename(): string;
    }
?>