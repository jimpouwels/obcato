<?php
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . '/modules/blocks/visuals/blocks/BlockMetadataEditor.php';

class BlockEditor extends Visual {

    private Block $currentBlock;

    public function __construct(Block $currentBlock) {
        parent::__construct();
        $this->currentBlock = $currentBlock;
    }

    public function getTemplateFilename(): string {
        return "modules/blocks/blocks/editor.tpl";
    }

    public function load(): void {
        $this->assign("current_block_id", $this->currentBlock->getId());
        $this->assign("block_metadata", $this->renderBlockMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainer());
        $this->assign("link_editor", $this->renderLinkEditor());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderBlockMetaDataPanel(): string {
        return (new BlockMetadataEditor($this->currentBlock))->render();
    }

    private function renderElementContainer(): string {
        return (new ElementContainer($this->currentBlock->getElements()))->render();
    }

    private function renderLinkEditor(): string {
        return (new LinkEditor($this->currentBlock->getLinks()))->render();
    }
}
