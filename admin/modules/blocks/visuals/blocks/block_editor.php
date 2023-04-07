<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/element_container.php";
    require_once CMS_ROOT . "view/views/link_editor.php";
    require_once CMS_ROOT . 'modules/blocks/visuals/blocks/block_metadata_editor.php';

    class BlockEditor extends Visual {

        private static $BLOCK_EDITOR_TEMPLATE = "modules/blocks/blocks/editor.tpl";

        private $_current_block;

        public function __construct($current_block) {
            parent::__construct();
            $this->_current_block = $current_block;
        }

        public function renderVisual(): string {
            $this->getTemplateEngine()->assign("block_metadata", $this->renderBlockMetaDataPanel());
            $this->getTemplateEngine()->assign("element_container", $this->renderElementContainer());
            $this->getTemplateEngine()->assign("link_editor", $this->renderLinkEditor());

            return $this->getTemplateEngine()->fetch(self::$BLOCK_EDITOR_TEMPLATE);
        }

        private function renderBlockMetaDataPanel() {
            $metadata_editor = new BlockMetadataEditor($this->_current_block);
            return $metadata_editor->render();
        }

        private function renderElementContainer() {
            $element_container = new ElementContainer($this->_current_block->getElements());
            return $element_container->render();
        }

        private function renderLinkEditor() {
            $link_editor = new LinkEditor($this->_current_block->getLinks());
            return $link_editor->render();
        }
    }
