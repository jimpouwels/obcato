<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class FormElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, FormElement $form_element) {
            parent::__construct($page, $article, $form_element);
        }

        public function getElementTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName();
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $data->assign("title", $this->getElement()->getTitle());
            $webform = null;
            if ($this->getElement()->getWebForm()) {
                $webform = $this->getElement()->getWebForm()->getTitle();
            }
            $data->assign("webform", $webform);
        }
    }