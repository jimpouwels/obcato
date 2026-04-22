<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\database\dao\TemplateDao;
use Pageflow\Core\database\dao\TemplateDaoMysql;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\templates\model\Presentable;
use const Pageflow\core\FRONTEND_TEMPLATE_DIR;

class BlockVisual extends FrontendVisual {

    private Block $block;
    private TemplateDao $templateDao;

    public function __construct(Block $block, Page $page, ?Article $article) {
        parent::__construct($page, $article, $block);
        $this->block = $block;
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->block->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadVisual(?array &$data): void {
        $data['id'] = $this->block->getId();
        $data['title'] =$this->block->getTitle();
        $this->renderElementHolderContent($this->block, $data);
    }

    public function getPresentable(): ?Presentable {
        return $this->block;
    }
}