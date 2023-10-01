<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/blocks/visuals/blocks/blocks_list.php";
    require_once CMS_ROOT . "modules/blocks/visuals/blocks/block_editor.php";
    
    class BlockTab extends Visual {
    
        private ?Block $_current_block;
    
        public function __construct(?Block $current_block) {
            parent::__construct();
            $this->_current_block = $current_block;
        }
        
        public function getTemplateFilename(): string {
            return "modules/blocks/blocks/root.tpl";
        }
    
        public function load(): void {
            $this->assign("blocks_list", $this->renderBlocksList());
            if (!is_null($this->_current_block)) {
                $this->assign("editor", $this->renderBlockEditor());
            }
        }
        
        private function renderBlocksList(): string {
            $blocks_list = new BlocksList($this->_current_block);
            return $blocks_list->render();
        }
        
        private function renderBlockEditor(): string {
            $block_editor = new BlockEditor($this->_current_block);
            return $block_editor->render();
        }
    
    }
    
?>