<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/element_visual.php';
    require_once CMS_ROOT . 'modules/webforms/webform_item_factory.php';
    require_once CMS_ROOT . 'frontend/handlers/form_status.php';

    class FormElementFrontendVisual extends ElementFrontendVisual {

        private WebFormItemFactory $_webform_item_factory;

        public function __construct(Page $page, ?Article $article, FormElement $form_element) {
            parent::__construct($page, $article, $form_element);
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
        }

        public function getElementTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . '/sa_form.tpl';
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $data->assign('webform_id', $this->getElement()->getWebForm()->getId());
            if ($this->getElement()->getWebForm()->getIncludeCaptcha()) {
                $captcha_key = $this->getElement()->getWebForm()->getCaptchaKey();
                $data->assign('captcha_key', $captcha_key);
            }
            $form_data = $this->createChildData();
            $form_data->assign('title', $this->getElement()->getTitle());
            $webform_data = array();
            if ($this->getElement()->getWebForm()) {
                $webform_data = $this->renderWebForm($this->getElement()->getWebForm());
            }
            $form_data->assign('webform', $webform_data);
            $data->assign('form_html', $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName(), $form_data));
        }

        private function renderWebForm(WebForm $webform): array {
            $webform_data = array();
            $webform_data['title'] = $webform->getTitle();
            $webform_data['fields'] = $this->renderFields($webform);
            $webform_data['is_submitted'] = FormStatus::getSubmittedForm() == $this->getElement()->getWebForm()->getId();
            return $webform_data;
        }

        private function renderFields(WebForm $webform): array {
            $fields = array();
            foreach ($webform->getFormFields() as $form_field) {
                $field = $this->_webform_item_factory->getFrontendVisualFor($webform, $form_field, $this->getPage(), $this->getArticle());
                $fields[] = $field->render();
            }
            return $fields;
        }
    }