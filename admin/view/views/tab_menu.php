<?php
    defined('_ACCESS') or die;
    
    class TabMenu extends Visual {
    
        private static $TEMPLATE = "system/tab_menu.tpl";
        private $_tab_items;
        private $_current_tab;
    
        public function __construct($tab_items, $current_tab) {
            parent::__construct();
            $this->_tab_items = $tab_items;
            $this->_current_tab = $current_tab;
        }
    
        public function renderVisual(): string {
            $this->getTemplateEngine()->assign("tab_items", $this->_tab_items);
            $this->getTemplateEngine()->assign("current_tab", $this->_current_tab);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }