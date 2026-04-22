<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\database\dao\TemplateDao;
use Pageflow\Core\database\dao\TemplateDaoMysql;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\webforms\model\Webform;
use Pageflow\Core\modules\webforms\model\WebformItem;
use const Pageflow\core\FRONTEND_TEMPLATE_DIR;

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