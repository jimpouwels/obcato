<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/database/database_pre_handler.php";
    require_once CMS_ROOT . "modules/database/visuals/configuration.php";
    require_once CMS_ROOT . "modules/database/visuals/tables.php";
    require_once CMS_ROOT . "modules/database/visuals/queries_tab.php";
    require_once CMS_ROOT . "view/views/tab_menu.php";

    class DatabaseModuleVisual extends ModuleVisual {

        private static $DATABASE_MODULE_TEMPLATE = "modules/database/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "modules/database/head_includes.tpl";
        private static $CONFIGURATION_TAB = 0;
        private static $TABLES_TAB = 1;
        private static $QUERY_TAB = 2;
        private $_database_module;
        private $_database_pre_handler;

        public function __construct($database_module) {
            parent::__construct($database_module);
            $this->_database_module = $database_module;
            $this->_database_pre_handler = new DatabasePreHandler();
        }

        public function render(): string {
            $this->getTemplateEngine()->assign("tab_menu", $this->renderTabMenu());
            if ($this->getCurrentTabId() == self::$CONFIGURATION_TAB) {
                $content = new Configuration();
            } else if ($this->getCurrentTabId() == self::$TABLES_TAB) {
                $content = new Tables();
            } else if ($this->getCurrentTabId() == self::$QUERY_TAB) {
                $content = new QueriesTab($this->_database_pre_handler);
            }
            $this->getTemplateEngine()->assign("content", $content->render());
            return $this->getTemplateEngine()->fetch(self::$DATABASE_MODULE_TEMPLATE);
        }

        public function getActionButtons() {
            $action_buttons = array();
            return $action_buttons;
        }

        public function renderHeadIncludes() {
            $this->getTemplateEngine()->assign("path", $this->_database_module->getIdentifier());
            return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $pre_handlers = array();
            $pre_handlers[] = $this->_database_pre_handler;
            return $pre_handlers;
        }

        public function onRequestHandled(): void {
        }

        private function renderTabMenu() {
            $tab_items = array();
            
            $tab_item = array();
            $tab_item["text"] = "Configuratie";
            $tab_item["id"] = self::$CONFIGURATION_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = "Tabellen";
            $tab_item["id"] = self::$TABLES_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = "Query";
            $tab_item["id"] = self::$QUERY_TAB;
            $tab_items[] = $tab_item;

            $tab_menu = new TabMenu($tab_items, $this->getCurrentTabId());
            return $tab_menu->render();
        }

    }

?>
