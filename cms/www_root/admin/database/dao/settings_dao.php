<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "database/mysql_connector.php";
	include_once FRONTEND_REQUEST . "core/data/settings.php";

	class SettingsDao {
	
		private static $instance;
		private $_mysql_database;
		
		private function __construct() {
			$this->_mysql_database = MysqlConnector::getInstance();
		}
		
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new SettingsDao();
			}
			return self::$instance;
		}
		
		public function getSettings() {
			$query = "SELECT * FROM settings";
			$result = $this->_mysql_database->executeSelectQuery($query);
			$settings = null;
			while ($row = mysql_fetch_assoc($result)) {
				$settings = self::constructFromRecord($row);
			}
			return $settings;
		}
		
		public function setHomepage($homepage_id) {
			$this->_mysql_database = MysqlConnector::getInstance();
			$query1 = "UPDATE pages SET is_homepage = 0";
			$query2 = "UPDATE pages SET is_homepage = 1 WHERE element_holder_id = $homepage_id";
			
			$this->_mysql_database->executeQuery($query1);
			$this->_mysql_database->executeQuery($query2);
		}
		
	}
?>