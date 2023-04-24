<?php
    defined('_ACCESS') or die;
    
    class TabMenu extends Visual {
    
        private array $_tab_items;
        private int $_current_tab;
    
        public function __construct(array $tab_items, int $current_tab) {
            parent::__construct();
            $this->_tab_items = $tab_items;
            $this->_current_tab = $current_tab;
        }

        public function getTemplateFilename(): string {
            return "system/tab_menu.tpl";
        }
    
        public function load(): void {
            $this->assign("tab_items", $this->_tab_items);
            $this->assign("current_tab", $this->_current_tab);
        }
    }