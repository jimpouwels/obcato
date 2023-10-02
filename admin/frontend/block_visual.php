<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/frontend/frontend_visual.php";
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';
require_once CMS_ROOT . '/database/dao/TemplateDaoMysql.php';

class BlockVisual extends FrontendVisual {

    private Block $_block;
    private TemplateDao $_template_dao;

    public function __construct(Block $block, Page $page) {
        parent::__construct($page, null);
        $this->_block = $block;
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->_block->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadVisual(?array &$data): void {
        $this->assign('id', $this->_block->getId());
        $this->assign('title', $this->_block->getTitle());
        $this->assign('elements', $this->renderElements());
    }

    public function getPresentable(): ?Presentable {
        return $this->_block;
    }

    private function renderElements(): array {
        $elements_content = array();
        foreach ($this->_block->getElements() as $element) {
            $elements_content[] = $element->getFrontendVisual($this->getPage(), null)->render();
        }
        return $elements_content;
    }
}