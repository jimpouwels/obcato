<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use const Obcato\core\FRONTEND_TEMPLATE_DIR;

class BlockVisual extends FrontendVisual {

    private Block $block;
    private TemplateDao $templateDao;

    public function __construct(Block $block, Page $page) {
        parent::__construct($page, null);
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