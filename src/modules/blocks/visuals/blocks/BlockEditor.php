<?php

namespace Obcato\Core\modules\blocks\visuals\blocks;

use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\view\views\ElementContainer;
use Obcato\Core\view\views\LinkEditor;
use Obcato\Core\view\views\Visual;
use const use Obcato\Core\ELEMENT_HOLDER_FORM_ID;

class BlockEditor extends Visual {

    private Block $currentBlock;

    public function __construct(Block $current) {
        parent::__construct();
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
        return (new BlockMetadataEditor($this->currentBlock))->render();
    }

    private function renderElementContainer(): string {
        return (new ElementContainer($this->currentBlock->getElements()))->render();
    }

    private function renderLinkEditor(): string {
        return (new LinkEditor($this->currentBlock->getLinks()))->render();
    }
}
