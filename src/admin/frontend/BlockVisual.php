<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\database\dao\TemplateDao;
use Obcato\Core\admin\database\dao\TemplateDaoMysql;
use Obcato\Core\admin\modules\blocks\model\Block;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\templates\model\Presentable;
use const Obcato\Core\admin\FRONTEND_TEMPLATE_DIR;

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
        $this->assign('id', $this->block->getId());
        $this->assign('title', $this->block->getTitle());
        $this->assign('elements', $this->renderElements());
    }

    public function getPresentable(): ?Presentable {
        return $this->block;
    }

    private function renderElements(): array {
        $elementsContent = array();
        foreach ($this->block->getElements() as $element) {
            $elementsContent[] = $element->getFrontendVisual($this->getPage(), null)->render();
        }
        return $elementsContent;
    }
}