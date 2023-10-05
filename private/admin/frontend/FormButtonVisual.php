<?php
require_once CMS_ROOT . '/frontend/FormItemVisual.php';
require_once CMS_ROOT . '/database/dao/TemplateDaoMysql.php';

class FormButtonVisual extends FormItemVisual {

    private TemplateDao $_template_dao;

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webform_item) {
        parent::__construct($page, $article, $webform, $webform_item);
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getFormItemTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getFormItem()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadFormItem(): void {}

}

?>