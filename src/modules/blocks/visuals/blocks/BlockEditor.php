<?php

namespace Pageflow\Core\modules\blocks\visuals\blocks;

use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\view\views\ElementContainer;
use Pageflow\Core\view\views\Visual;
use const Pageflow\core\ELEMENT_HOLDER_FORM_ID;

class BlockEditor extends Visual {

    private Block $currentBlock;

    public function __construct(Block $current) {
        parent::__construct();
        $this->currentBlock = $current;
    }

    public function getTemplateFilename(): string {
        return "blocks/templates/blocks/editor.tpl";
    }

    public function load(): void {
        $this->assign("current_block_id", $this->currentBlock->getId());
        $this->assign("block_metadata", $this->renderBlockMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainer());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderBlockMetaDataPanel(): string {
        return (new BlockMetadataEditor($this->currentBlock))->render();
    }

    private function renderElementContainer(): string {
        return (new ElementContainer($this->currentBlock->getElements()))->render();
    }

}
