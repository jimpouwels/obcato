<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'frontend/element_visual.php';
    require_once CMS_ROOT . 'modules/webforms/webform_item_factory.php';
    require_once CMS_ROOT . 'frontend/handlers/form_status.php';

    class FormFrontendVisual extends FrontendVisual {

    private WebFormItemFactory $_webform_item_factory;

        private WebForm $_webform;
        private TemplateDao $_template_dao;

        public function __construct(Page $page, ?Article $article, WebForm $webform) {
            parent::__construct($page, $article);
            $this->_webform = $webform;
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
            $this->_template_dao = TemplateDao::getInstance();
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . '/sa_form.tpl';
        }

        public function loadVisual(Smarty_Internal_Data $template_data, ?array &$data): void {
            $this->assign('webform_id', $this->_webform->getId());
            if ($this->_webform->getIncludeCaptcha()) {
                $captcha_key = $this->_webform->getCaptchaKey();
                $this->assign('captcha_key', $captcha_key);
            }
            $this->assign('title', $this->_webform->getTitle());

            $webform_child_data = $this->createChildData();
            $webform_data = array();
            if ($this->_webform) {
                $webform_data = $this->renderWebForm($this->_webform);
            }
            $webform_child_data->assign('webform', $webform_data);
            $this->assign('form_html', $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->_webform->getTemplate()->getTemplateFileId())->getFileName(), $webform_child_data));
        }

        public function getPresentable(): ?Presentable {
            return $this->_webform;
        }

        private function renderWebForm(WebForm $webform): array {
            $webform_data = array();
            $webform_data['title'] = $webform->getTitle();
            $webform_data['fields'] = $this->renderFields($webform);
            $webform_data['is_submitted'] = FormStatus::getSubmittedForm() == $this->_webform->getId();
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