<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/form_template_picker.php";
require_once CMS_ROOT . "view/views/element_container.php";
require_once CMS_ROOT . "view/views/link_editor.php";
require_once CMS_ROOT . "view/views/block_selector.php";
require_once CMS_ROOT . 'modules/pages/visuals/page_metadata_editor.php';

class PageEditor extends Visual {

    private Page $_current_page;

    public function __construct(Page $current_page) {
        parent::__construct();
        $this->_current_page = $current_page;
    }

    public function getTemplateFilename(): string {
        return "modules/pages/editor.tpl";
    }

    public function load(): void {
        $this->assign("page_id", $this->_current_page->getId());
        $this->assign("page_metadata", $this->renderPageMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainerPanel());
        $this->assign("link_editor", $this->renderLinkEditorPanel());
        $this->assign("block_selector", $this->renderBlockSelectorPanel());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderPageMetaDataPanel(): string {
        $metadata_editor = new MetadataEditor($this->_current_page);
        return $metadata_editor->render();
    }

    private function renderElementContainerPanel(): string {
        $element_container = new ElementContainer($this->_current_page->getElements());
        return $element_container->render();
    }

    private function renderLinkEditorPanel(): string {
        $link_editor = new LinkEditor($this->_current_page->getLinks());
        return $link_editor->render();
    }

    private function renderBlockSelectorPanel(): string {
        $block_selector = new BlockSelector($this->_current_page->getBlocks(), $this->_current_page->getId());
        return $block_selector->render();
    }

}
