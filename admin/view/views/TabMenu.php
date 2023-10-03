<?php
defined('_ACCESS') or die;

class TabMenu extends Visual {

    private array $_tab_items = array();
    private int $_current_tab_id = 0;

    public function __construct($current_tab_id) {
        parent::__construct();
        $this->_current_tab_id = $current_tab_id;
    }

    public function getTemplateFilename(): string {
        return "system/tab_menu.tpl";
    }

    public function load(): void {
        $this->assign("tab_items", $this->_tab_items);
        $this->assign("current_tab", $this->_current_tab_id);
    }

    public function addItem(string $text_resource_identifier, int $id): void {
        $tab_item = array();
        $tab_item["text_resource_identifier"] = $text_resource_identifier;
        $tab_item["id"] = $id;
        $this->_tab_items[] = $tab_item;
    }
}