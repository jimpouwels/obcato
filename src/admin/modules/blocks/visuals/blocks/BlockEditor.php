<?php

namespace Obcato\Core\admin\modules\blocks\visuals\blocks;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\blocks\model\Block;
use Obcato\Core\admin\view\views\ElementContainer;
use Obcato\Core\admin\view\views\LinkEditor;
use Obcato\Core\admin\view\views\Visual;
use const Obcato\Core\admin\ELEMENT_HOLDER_FORM_ID;

class BlockEditor extends Visual {

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
