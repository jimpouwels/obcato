<?php
require_once CMS_ROOT . '/frontend/FormItemVisual.php';
require_once CMS_ROOT . '/database/dao/TemplateDaoMysql.php';

class FormButtonVisual extends FormItemVisual {

    private TemplateDao $templateDao;

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webformItem) {
        parent::__construct($page, $article, $webform, $webformItem);
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getFormItemTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadFormItem(): void {}

}