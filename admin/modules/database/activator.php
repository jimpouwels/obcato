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
        private $_template_engine;

        public function __construct($database_module) {
            parent::__construct($database_module);
            $this->_database_module = $database_module;
            $this->_database_pre_handler = new DatabasePreHandler();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign("tab_menu", $this->renderTabMenu());
            if ($this->_database_pre_handler->getCurrentTabId() == self::$CONFIGURATION_TAB)
                $content = new Configuration();
            else if ($this->_database_pre_handler->getCurrentTabId() == self::$TABLES_TAB)
                $content = new Tables();
            else if ($this->_database_pre_handler->getCurrentTabId() == self::$QUERY_TAB)
                $content = new QueriesTab($this->_database_pre_handler);
            $this->_template_engine->assign("content", $content->render());
            return $this->_template_engine->fetch(self::$DATABASE_MODULE_TEMPLATE);
        }

        public function getActionButtons() {
            $action_buttons = array();
            return $action_buttons;
        }

        public function getHeadIncludes() {
            $this->_template_engine->assign("path", $this->_database_module->getIdentifier());
            return $this->_template_engine->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $pre_handlers = array();
            $pre_handlers[] = $this->_database_pre_handler;
            return $pre_handlers;
        }

        public function onPreHandled() {
        }

        private function renderTabMenu() {
            $tab_items = array();
            $tab_items[self::$CONFIGURATION_TAB] = "Configuratie";
            $tab_items[self::$TABLES_TAB] = "Tabellen";
            $tab_items[self::$QUERY_TAB] = "Query";
            $tab_menu = new TabMenu($tab_items, $this->_database_pre_handler->getCurrentTabId());
            return $tab_menu->render();
        }

    }

?>
