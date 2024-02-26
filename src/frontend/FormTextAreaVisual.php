<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\model\WebformField;
use const use Obcato\Core\FRONTEND_TEMPLATE_DIR;

class FormTextAreaVisual extends FormFieldVisual {
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