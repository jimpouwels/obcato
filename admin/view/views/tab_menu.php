<?php
    defined('_ACCESS') or die;
    
    class TabMenu extends Visual {
    
        private static string $TEMPLATE = "system/tab_menu.tpl";
        private array $_tab_items;
        private int $_current_tab;
    
        public function __construct(array $tab_items, int $current_tab) {
            parent::__construct();
            $this->_tab_items = $tab_items;
            $this->_current_tab = $current_tab;
        }
    
        public function render(): string {
            $this->getTemplateEngine()->assign("tab_items", $this->_tab_items);
            $this->getTemplateEngine()->assign("current_tab", $this->_current_tab);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }