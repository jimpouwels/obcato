<?php

	// No direct access
	defined('_ACCESS') or die;

	class MysqlConnector {
	
		private static $instance;
		private $conn;
		private $_host;
		private $_databse_name;
		
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new MysqlConnector();				
			}
			return self::$instance;
		}
		
		private function __construct() {
			include_once FRONTEND_REQUEST . "database_config.php";
			$this->_host = HOST;
			$this->_database_name = DATABASE_NAME;
			$this->conn = mysql_connect($this->_host, USERNAME, PASSWORD) or die("Error connecting to MySQL database");
			mysql_select_db($this->_database_name, $this->conn);
		}

		public function getConnection() {
			return $this->conn; 
		}

		public function executeSelectQuery($query) {
			$result = null;
			$result = mysql_query($query, $this->conn);		
			return $result;
		}
		
		public function executeQuery($query) {
			$result = mysql_query($query, $this->conn);
		}
		
		public function getNextIdValue($table_name) {
			$query = "SELECT MAX(id) as next_id FROM " . $table_name;
			$result = self::executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				return $row['next_id'] + 1;
			}
		}
		
		public function getDatabaseName() {
			return $this->_database_name;
		}
		
		public function getHostName() {
			return $this->_host;
		}
		
		public function getDatabaseType() {
			return "MySQL";
		}
		
		public function getDatabaseVersion() {
			$query = "select version() AS version";
			$result = self::executeSelectQuery($query);
			while ($row = mysql_fetch_assoc($result)) {
				return $row['version'];
			}
		}
	}
	
?>