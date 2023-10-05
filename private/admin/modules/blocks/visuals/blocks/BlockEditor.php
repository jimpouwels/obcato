<?php
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . '/modules/blocks/visuals/blocks/BlockMetadataEditor.php';

class BlockEditor extends Visual {

    private Block $_current_block;

    public function __construct(Block $current_block) {
        parent::__construct();
        $this->_current_block = $current_block;
    }

    public function getTemplateFilename(): string {
        return "modules/blocks/blocks/editor.tpl";
    }

    public function load(): void {
        $this->assign("current_block_id", $this->_current_block->getId());
        $this->assign("block_metadata", $this->renderBlockMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainer());
        $this->assign("link_editor", $this->renderLinkEditor());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderBlockMetaDataPanel(): string {
        $metadata_editor = new BlockMetadataEditor($this->_current_block);
        return $metadata_editor->render();
    }

    private function renderElementContainer(): string {
        $element_container = new ElementContainer($this->_current_block->getElements());
        return $element_container->render();
    }

    private function renderLinkEditor(): string {
        $link_editor = new LinkEditor($this->_current_block->getLinks());
        return $link_editor->render();
    }
}
