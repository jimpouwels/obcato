<?php

namespace Obcato\Core\view\views;

class TabMenu extends Visual {

    private array $tabItems = array();
    private int $currentTabId;

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "tab_menu.tpl";
    }

    public function load(): void {
        $this->assign("tab_items", $this->tabItems);
        $this->assign("current_tab", $this->currentTabId);
    }

    public function addItem(string $textResourceIdentifier, int $id): void {
        $tab_item = array();
        $tab_item["text_resource_identifier"] = $textResourceIdentifier;
        $tab_item["id"] = $id;
        $this->tabItems[] = $tab_item;
    }

    public function setCurrentTabId(int $tabId): void {
        $this->currentTabId = $tabId;
    }
}