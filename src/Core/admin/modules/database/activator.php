<?php

namespace Obcato\Core;

use Obcato\ComponentApi\ModuleVisual;
use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;

class DatabaseModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/database/head_includes.tpl";
    private static int $CONFIGURATION_TAB = 0;
    private static int $TABLES_TAB = 1;
    private static int $QUERY_TAB = 2;
    private Module $module;
    private DatabaseRequestHandler $databaseRequestHandler;

    public function __construct(TemplateEngine $templateEngine, Module $module) {
        parent::__construct($templateEngine, $module);
        $this->module = $module;
        $this->databaseRequestHandler = new DatabaseRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/database/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->getCurrentTabId() == self::$CONFIGURATION_TAB) {
            $content = new Configuration($this->getTemplateEngine());
        } else if ($this->getCurrentTabId() == self::$TABLES_TAB) {
            $content = new Tables($this->getTemplateEngine());
        } else if ($this->getCurrentTabId() == self::$QUERY_TAB) {
            $content = new QueriesTab($this->getTemplateEngine(), $this->databaseRequestHandler);
        }
        $this->assign("content", $content?->render());
    }

    public function getActionButtons(): array {
        return array();
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->module->getIdentifier());
        return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->databaseRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): void {
        $tabMenu->addItem("database_tab_menu_configuration", self::$CONFIGURATION_TAB);
        $tabMenu->addItem("database_tab_menu_tabels", self::$TABLES_TAB);
        $tabMenu->addItem("database_tab_menu_query", self::$QUERY_TAB);
    }
}