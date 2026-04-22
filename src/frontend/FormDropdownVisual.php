<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\database\dao\TemplateDao;
use Pageflow\Core\database\dao\TemplateDaoMysql;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\webforms\model\Webform;
use Pageflow\Core\modules\webforms\model\WebformField;
use const Pageflow\core\FRONTEND_TEMPLATE_DIR;

class FormDropDownVisual extends FormFieldVisual {

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