<?php

namespace Obcato\Core\admin\frontend;

class FormTextAreaVisual extends FormFieldVisual {
    private TemplateDao $_template_dao;

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormField $webformField) {
        parent::__construct($page, $article, $webform, $webformField);
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getFormFieldTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadFormField(): void {}

}