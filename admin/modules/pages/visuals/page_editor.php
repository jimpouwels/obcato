<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/form_template_picker.php";
    require_once CMS_ROOT . "view/views/element_container.php";
    require_once CMS_ROOT . "view/views/link_editor.php";
    require_once CMS_ROOT . "view/views/block_selector.php";
    require_once CMS_ROOT . 'modules/pages/visuals/page_metadata_editor.php';

    class PageEditor extends Visual {

        private static string $PAGE_EDITOR_TEMPLATE = "pages/editor.tpl";
        private static string $PAGE_METADATA_TEMPLATE = "pages/metadata.tpl";

        private Page $_current_page;

        public function __construct(Page $current_page) {
            parent::__construct();
            $this->_current_page = $current_page;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign("page_id", $this->_current_page->getId());
            $this->getTemplateEngine()->assign("page_metadata", $this->renderPageMetaDataPanel());
            $this->getTemplateEngine()->assign("element_container", $this->renderElementContainerPanel());
            $this->getTemplateEngine()->assign("link_editor", $this->renderLinkEditorPanel());
            $this->getTemplateEngine()->assign("block_selector", $this->renderBlockSelectorPanel());
            return $this->getTemplateEngine()->fetch("modules/" . self::$PAGE_EDITOR_TEMPLATE);
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
