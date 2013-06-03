<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/system/mysql_connector.php";
	include_once "modules/database/domain/table_column.php";
	
	class DatabaseDao {
		
		// singleton
		private static $instance;
		
		/*
			Creates (if not exists) and returns an instance.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new DatabaseDao();
			}
			return self::$instance;
		}
		
		/*
			Returns all tables from the database.
		*/
		public function getTables() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SHOW TABLES";
			$result = $mysql_database->executeSelectQuery($query);
			
			$mysql_database = MysqlConnector::getInstance();
			$database_name = $mysql_database->getDatabaseName();
			$tables = array();
			
			while ($row = mysql_fetch_assoc($result)) {
				$tables[] = $row['Tables_in_' . $database_name];;
			}
			return $tables;
		}
		
		/*
			Returns all columns for the given table.
			
			@param $table The table to get the columns for
		*/
		public function getColumns($table_name) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = 'SHOW columns FROM ' . $table_name;
			$result = $mysql_database->executeSelectQuery($query);
			
			$columns = array();
			$column = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$column = new TableColumn();
				$column->setName($row['Field']);
				$column->setType($row['Type']);
				$column->setAllowedNull($row['Null']);
				
				$columns[] = $column;
			}
			
			return $columns;
		}
	
	}
	
?>