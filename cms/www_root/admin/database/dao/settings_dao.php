<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "database/mysql_connector.php";
	include_once FRONTEND_REQUEST . "core/data/settings.php";

	class SettingsDao {
	
		// singleton
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates and returns a new instance of the DAO
			if it not yet exists.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new SettingsDao();
			}
			return self::$instance;
		}
		
		/*
			Sets the homepage.
			
			@param $homepage_id The homepage ID to set
		*/
		public function setHomepage($homepage_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query1 = "UPDATE pages SET is_homepage = 0";
			$query2 = "UPDATE pages SET is_homepage = 1 WHERE element_holder_id = $homepage_id";
			
			$mysql_database->executeQuery($query1);
			$mysql_database->executeQuery($query2);
		}
		
	}
?>