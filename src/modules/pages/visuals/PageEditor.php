<?php

namespace Pageflow\Core\modules\pages\visuals;

use Pageflow\Core\database\dao\BlockDao;
use Pageflow\Core\database\dao\BlockDaoMysql;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\view\views\BlockSelector;
use Pageflow\Core\view\views\ElementContainer;
use Pageflow\Core\view\views\Visual;
use const Pageflow\core\ELEMENT_HOLDER_FORM_ID;

class PageEditor extends Visual {

    private Page $currentPage;
    private BlockDao $blockDao;

    public function __construct(Page $currentPage) {
        parent::__construct();
        $this->blockDao = BlockDaoMysql::getInstance();
        $this->currentPage = $currentPage;
    }

    public function getTemplateFilename(): string {
        return "pages/templates/editor.tpl";
    }

    public function load(): void {
        $this->assign("page_id", $this->currentPage->getId());
        $this->assign("page_metadata", $this->renderPageMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainerPanel());
        $this->assign("block_selector", $this->renderBlockSelectorPanel());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderPageMetaDataPanel(): string {
        return (new MetadataEditor($this->currentPage))->render();
    }

    private function renderElementContainerPanel(): string {
        return (new ElementContainer($this->currentPage->getElements()))->render();
    }

    private function renderBlockSelectorPanel(): string {
        return (new BlockSelector($this->blockDao->getBlocksByPage($this->currentPage), $this->currentPage->getId()))->render();
    }

}
