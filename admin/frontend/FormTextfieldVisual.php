<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/frontend/FormFieldVisual.php';

class FormTextFieldVisual extends FormFieldVisual {

    private TemplateDao $_template_dao;

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormField $webform_field) {
        parent::__construct($page, $article, $webform, $webform_field);
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getFormFieldTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadFormField(): void {}

}

?>