<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/module_visual.php";
require_once CMS_ROOT . "modules/database/database_request_handler.php";
require_once CMS_ROOT . "modules/database/visuals/configuration.php";
require_once CMS_ROOT . "modules/database/visuals/tables.php";
require_once CMS_ROOT . "modules/database/visuals/queries_tab.php";
require_once CMS_ROOT . "view/views/tab_menu.php";

class DatabaseModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/database/head_includes.tpl";
    private static int $CONFIGURATION_TAB = 0;
    private static int $TABLES_TAB = 1;
    private static int $QUERY_TAB = 2;
    private Module $_database_module;
    private DatabaseRequestHandler $_database_request_handler;

    public function __construct(Module $database_module) {
        parent::__construct($database_module);
        $this->_database_module = $database_module;
        $this->_database_request_handler = new DatabaseRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/database/root.tpl";
    }

    public function load(): void {
        if ($this->getCurrentTabId() == self::$CONFIGURATION_TAB) {
            $content = new Configuration();
        } else if ($this->getCurrentTabId() == self::$TABLES_TAB) {
            $content = new Tables();
        } else if ($this->getCurrentTabId() == self::$QUERY_TAB) {
            $content = new QueriesTab($this->_database_request_handler);
        }
        $this->assign("content", $content->render());
    }

    public function getActionButtons(): array {
        return array();
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->_database_module->getIdentifier());
        return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        $request_handlers[] = $this->_database_request_handler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        $tab_menu = new TabMenu($this->getCurrentTabId());
        $tab_menu->addItem("database_tab_menu_configuration", self::$CONFIGURATION_TAB);
        $tab_menu->addItem("database_tab_menu_tabels", self::$TABLES_TAB);
        $tab_menu->addItem("database_tab_menu_query", self::$QUERY_TAB);
        return $tab_menu;
    }

}

?>
