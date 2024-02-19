<?php
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . '/modules/blocks/visuals/blocks/BlockMetadataEditor.php';

class BlockEditor extends Obcato\ComponentApi\Visual {

    private Block $currentBlock;

    public function __construct(TemplateEngine $templateEngine, Block $current) {
        parent::__construct($templateEngine);
        $this->currentBlock = $current;
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
        return (new BlockMetadataEditor($this->getTemplateEngine(), $this->currentBlock))->render();
    }

    private function renderElementContainer(): string {
        return (new ElementContainer($this->getTemplateEngine(), $this->currentBlock->getElements()))->render();
    }

    private function renderLinkEditor(): string {
        return (new LinkEditor($this->getTemplateEngine(), $this->currentBlock->getLinks()))->render();
    }
}
