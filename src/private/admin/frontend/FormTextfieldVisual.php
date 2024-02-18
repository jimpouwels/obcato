<?php
require_once CMS_ROOT . '/frontend/FormFieldVisual.php';

class FormTextFieldVisual extends FormFieldVisual {

    private TemplateDao $templateDao;

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormField $webformField) {
        parent::__construct($page, $article, $webform, $webformField);
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getFormFieldTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadFormField(): void {}

}

?>