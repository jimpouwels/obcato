<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\database\dao\TemplateDao;
use Obcato\Core\admin\database\dao\TemplateDaoMysql;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\webforms\model\Webform;
use Obcato\Core\admin\modules\webforms\model\WebformItem;
use const Obcato\Core\admin\FRONTEND_TEMPLATE_DIR;

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