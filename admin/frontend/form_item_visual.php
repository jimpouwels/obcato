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

        public function loadVisual(?array &$data): void {
            $this->assign('label', $this->getFormItem()->getLabel());
            $this->assign('name', $this->getFormItem()->getName());
            $this->assign('value', FormStatus::getFieldValue($this->getFormItem()->getName()));
            $this->assign('has_error', FormStatus::getError($this->getFormItem()->getName()) != null);
            
            $this->loadFormItem();
            $this->assign('form_item_html', $this->fetch($this->getFormItemTemplateFilename()));
        }

        public function getPresentable(): ?Presentable {
            return $this->_webform_item;
        }

        abstract function loadFormItem(): void;
        abstract function getFormItemTemplateFilename(): string;
    }
?>