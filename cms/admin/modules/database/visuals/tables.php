<?php
	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "database/mysql_connector.php";
	require_once CMS_ROOT . "database/dao/database_dao.php";
	
	class Tables extends Visual {
	
		private static $TABLES_TEMPLATE = "modules/database/tables.tpl";
		private $_template_engine;
		private $_database_dao;
	
		public function __construct() {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_database_dao = DatabaseDao::getInstance();
		}
	
		public function render() {		
			$this->_template_engine->assign("tables", $this->getTables());
			return $this->_template_engine->fetch(self::$TABLES_TEMPLATE);
		}
		
		private function getTables() {
			$tables = $this->_database_dao->getTables();
			$tables_array = array();
			foreach ($tables as $table) {
				$table_value = array();
				$table_value["name"] = $table;
				$table_value["columns"] = $this->getColumns($table);
				$tables_array[] = $table_value;
			}
			return $tables_array;
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
	
?>