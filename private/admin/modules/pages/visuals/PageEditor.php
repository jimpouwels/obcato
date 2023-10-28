<?php
require_once CMS_ROOT . "/view/views/TemplatePicker.php";
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . "/view/views/BlockSelector.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . '/modules/pages/visuals/MetadataEditor.php';

class PageEditor extends Visual {

    private Page $currentPage;
    private BlockDao $blockDao;

    public function __construct(Page $currentPage) {
        parent::__construct();
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
        return (new MetadataEditor($this->currentPage))->render();
    }

    private function renderElementContainerPanel(): string {
        return (new ElementContainer($this->currentPage->getElements()))->render();
    }

    private function renderLinkEditorPanel(): string {
        return (new LinkEditor($this->currentPage->getLinks()))->render();
    }

    private function renderBlockSelectorPanel(): string {
        return (new BlockSelector($this->blockDao->getBlocksByPage($this->currentPage), $this->currentPage->getId()))->render();
    }

}
