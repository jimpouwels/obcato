<?php

namespace Pageflow\Core\modules\database;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\modules\database\visuals\Configuration;
use Pageflow\Core\modules\database\visuals\QueriesTab;
use Pageflow\Core\modules\database\visuals\Tables;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;

class DatabaseModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "database/templates/head_includes.tpl";
    private static int $CONFIGURATION_TAB = 0;
    private static int $TABLES_TAB = 1;
    private static int $QUERY_TAB = 2;
    private Module $module;
    private DatabaseRequestHandler $databaseRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->module = $module;
        $this->databaseRequestHandler = new DatabaseRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "database/templates/root.tpl";
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

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("database/templates/styles/database.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        $scripts[] = $this->getTemplateEngine()->fetch("database/templates/scripts/module_database.js.tpl");
        return $scripts;
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