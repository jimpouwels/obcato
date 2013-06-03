<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "dao/settings_dao.php";

	class MysqlConnector {
	
		// singleton
		private static $instance;
		private $conn;
		
		private $myHost;
		private $myPort;
		private $myPassword;
		private $myUsername;
		private $myDbName;
		
		/*
			Creates a new instance of the Database class.
		*/
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new MysqlConnector();
				
				// read settings
				
			}
			return self::$instance;
		}
		
		private function __construct() {
			$this->myHost = '127.0.0.1';
			$this->myPort = '3306';
			$this->myPassword = '';
			$this->myUsername = 'root';
			$this->myDbName = 'site_administration';
			$this->conn = mysql_connect($this->myHost, $this->myUsername, $this->myPassword) or die("Error connecting to MySQL database");
			mysql_select_db($this->myDbName, $this->conn);
		}
	
		/*
			Creates a new connection.
		*/
		public function getConnection() {
			return $this->conn; 
		}
		
		/*
			Executes the given query.
			
			@param $query The query to execute
			@param $conn The connection to the database
		*/
		public function executeSelectQuery($query) {
			$result = NULL;
			$result = mysql_query($query, $this->conn);
			
			$this->checkMySqlError($query);
			
			return $result;
		}
		
		/*
			Executes the given query.
			
			@param $query The query to execute
			@param $conn The connection to the database			
		*/
		public function executeQuery($query) {
			$result = mysql_query($query, $this->conn);
			$this->checkMySqlError($query);
		}
		
		/*
			Returns the new ID of given table.
		*/
		public function getNextIdValue($table_name) {
			$query = "SELECT MAX(id) as next_id FROM " . $table_name;
			$result = self::executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				return $row['next_id'] + 1;
			}
		}
		
		// Returns the database name
		public function getDatabaseName() {
			return $this->myDbName;
		}
		
		// returns the hostname
		public function getHostName() {
			return $this->myHost;
		}
		
		// returns the port
		public function getPort() {
			return $this->myPort;
		}
		
		// returns the database type
		public function getDatabaseType() {
			return "MySQL";
		}
		
		// returns the database version
		public function getDatabaseVersion() {
			$query = "select version() AS version";
			$result = self::executeSelectQuery($query);
			while ($row = mysql_fetch_assoc($result)) {
				return $row['version'];
			}
		}
		
		private function checkMySqlError($query) {
		
		}
	}
	
?>