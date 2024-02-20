<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TabMenu as ITabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class TabMenu extends Visual implements ITabMenu {

    private array $tabItems = array();
    private int $currentTabId = 0;

    public function __construct(TemplateEngine $templateEngine, int $currentTabId) {
        parent::__construct($templateEngine);
        $this->currentTabId = $currentTabId;
    }

    public function getTemplateFilename(): string {
        return "system/tab_menu.tpl";
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
}