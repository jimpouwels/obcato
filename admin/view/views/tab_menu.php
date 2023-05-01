<?php
    defined('_ACCESS') or die;
    
    class TabMenu extends Visual {
    
        private array $_tab_items = array();
        private int $_current_tab = 0;
    
        public function __construct() {
            parent::__construct();
        }

        public function getTemplateFilename(): string {
            return "system/tab_menu.tpl";
        }
    
        public function load(): void {
            $this->assign("tab_items", $this->_tab_items);
            $this->assign("current_tab", $this->_current_tab);
        }

        public function addItem(string $text_resource_identifier, int $id, bool $is_current = false): void {
            $tab_item = array();
            $tab_item["text_resource_identifier"] = $text_resource_identifier;
            $tab_item["id"] = $id;
            if ($is_current) {
                $this->_current_tab = $id;
            }
            $this->_tab_items[] = $tab_item;
        }
    }