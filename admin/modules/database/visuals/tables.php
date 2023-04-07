<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/database_dao.php";
    require_once CMS_ROOT . 'modules/database/visuals/table.php';

    class Tables extends Visual {

        private static $TABLES_TEMPLATE = "modules/database/tables.tpl";
        private $_template_engine;
        private $_database_dao;

        public function __construct() {
            parent::__construct();
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_database_dao = DatabaseDao::getInstance();
        }

        public function renderVisual(): string {
            $this->_template_engine->assign('tables', $this->getTables());
            return $this->_template_engine->fetch(self::$TABLES_TEMPLATE);
        }

        private function getTables() {
            $tables = $this->_database_dao->getTables();
            $table_panels = array();
            foreach ($tables as $table) {
                $table_value = array();
                $table_value["name"] = $table;
                $table_value["columns"] = $this->getColumns($table);
                $table_panel = new TablePanel($table_value);
                $table_panels[] = $table_panel->render();
            }
            return $table_panels;
        }

        private function getColumns($table) {
            $columns_array = array();
            foreach ($this->_database_dao->getColumns($table) as $column) {
                $column_value = array();
                $column_value["name"] = $column["name"];
                $column_value["type"] = $column["type"];
                $column_value["allowed_null"] = $column["allowed_null"] == "YES" ? "Ja" : "Nee";
                $columns_array[] = $column_value;
            }
            return $columns_array;
        }
    }
