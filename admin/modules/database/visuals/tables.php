<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/database/dao/DatabaseDaoMysql.php";
require_once CMS_ROOT . '/modules/database/visuals/table.php';

class Tables extends Visual {

    private DatabaseDao $_database_dao;

    public function __construct() {
        parent::__construct();
        $this->_database_dao = DatabaseDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/database/tables.tpl";
    }

    public function load(): void {
        $this->assign('tables', $this->getTables());
    }

    private function getTables(): array {
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

    private function getColumns(string $table): array {
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
