<?php

namespace Obcato\Core\admin\modules\database;

use Obcato\ComponentApi\TabMenu;
use Obcato\Core\admin\modules\database\visuals\Configuration;
use Obcato\Core\admin\modules\database\visuals\QueriesTab;
use Obcato\Core\admin\modules\database\visuals\Tables;
use Obcato\Core\admin\view\views\ModuleVisual;

class DatabaseModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/database/head_includes.tpl";
    private static int $CONFIGURATION_TAB = 0;
    private static int $TABLES_TAB = 1;
    private static int $QUERY_TAB = 2;
    private DatabaseRequestHandler $databaseRequestHandler;

    public function __construct() {
        parent::__construct();
        $this->databaseRequestHandler = new DatabaseRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/database/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->getCurrentTabId() == self::$CONFIGURATION_TAB) {
            $content = new Configuration();
        } else if ($this->getCurrentTabId() == self::$TABLES_TAB) {
            $content = new Tables();
        } else if ($this->getCurrentTabId() == self::$QUERY_TAB) {
            $content = new QueriesTab($this->databaseRequestHandler);
        }
        $this->assign("content", $content?->render());
    }

    public function getActionButtons(): array {
        return array();
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->getModuleIdentifier());
        return $this->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->databaseRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("database_tab_menu_configuration", self::$CONFIGURATION_TAB);
        $tabMenu->addItem("database_tab_menu_tabels", self::$TABLES_TAB);
        $tabMenu->addItem("database_tab_menu_query", self::$QUERY_TAB);
        return $this->getCurrentTabId();
    }
}