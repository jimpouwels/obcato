<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class PageEditor extends Visual {

    private Page $currentPage;
    private BlockDao $blockDao;

    public function __construct(TemplateEngine $templateEngine, Page $currentPage) {
        parent::__construct($templateEngine);
        $this->blockDao = BlockDaoMysql::getInstance();
        $this->currentPage = $currentPage;
    }

    public function getTemplateFilename(): string {
        return "modules/pages/editor.tpl";
    }

    public function load(): void {
        $this->assign("page_id", $this->currentPage->getId());
        $this->assign("page_metadata", $this->renderPageMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainerPanel());
        $this->assign("link_editor", $this->renderLinkEditorPanel());
        $this->assign("block_selector", $this->renderBlockSelectorPanel());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderPageMetaDataPanel(): string {
        return (new MetadataEditor($this->getTemplateEngine(), $this->currentPage))->render();
    }

    private function renderElementContainerPanel(): string {
        return (new ElementContainer($this->getTemplateEngine(), $this->currentPage->getElements()))->render();
    }

    private function renderLinkEditorPanel(): string {
        return (new LinkEditor($this->getTemplateEngine(), $this->currentPage->getLinks()))->render();
    }

    private function renderBlockSelectorPanel(): string {
        return (new BlockSelector($this->getTemplateEngine(), $this->blockDao->getBlocksByPage($this->currentPage), $this->currentPage->getId()))->render();
    }

}
