<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "libraries/system/mysql_connector.php";
	include_once FRONTEND_REQUEST . "core/data/module_group.php";
	include_once FRONTEND_REQUEST . "core/data/module.php";

	class ModuleDao {
	
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
				self::$instance = new ModuleDao();
			}
			return self::$instance;
		}
		
		/*
			Returns all default modules.
		*/
		public function getDefaultModules() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE system_default = 1 ORDER BY title";
			$result = $mysql_database->executeSelectQuery($query);
			$modules = array();
			while ($row = mysql_fetch_assoc($result)) {
				$module = Module::constructFromRecord($row);
				
				array_push($modules, $module);
			}
			
			return $modules;
		}
		
		/*
			Returns all custom modules.
		*/
		public function getCustomModules() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE system_default = 0 ORDER by title";
			$result = $mysql_database->executeSelectQuery($query);
			$modules = array();
			while ($row = mysql_fetch_assoc($result)) {
				$module = Module::constructFromRecord($row);
				
				array_push($modules, $module);
			}
			
			return $modules;
		}
		
		/*
			Returns the module with the given ID.
			
			@param $id The ID of the module to find
		*/
		public function getModule($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE id = " . $id;
			$result = $mysql_database->executeSelectQuery($query);
			
			$module = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$module = Module::constructFromRecord($row);
				
				break;
			}
			return $module;
		}
		
		/*
			Returns the module with the given identifier.
			
			@param $identifier The ID of the module to find
		*/
		public function getModuleByIdentifier($identifier) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE identifier = '" . $identifier . "'";
			$result = $mysql_database->executeSelectQuery($query);
			
			$module = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$module = Module::constructFromRecord($row);
				
				break;
			}
			return $module;
		}
		
		/*
			Returns all module groups.
		*/
		public function getModuleGroups() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM module_groups ORDER BY follow_up";
			$result = $mysql_database->executeSelectQuery($query);
			$groups = array();
			
			while ($row = mysql_fetch_array($result)) {
				$module_group = ModuleGroup::constructFromRecord($row);
				
				array_push($groups, $module_group);
			}
			
			return $groups;
		}
		
		/*
			Return all modules that should be shown on the homepage
		*/
		public function getHomeModules() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE home_item = 1";
			$result = $mysql_database->executeSelectQuery($query);
			$modules = array();
			while ($row = mysql_fetch_assoc($result)) {
				$module = Module::constructFromRecord($row);
				
				array_push($modules, $module);
			}
			
			return $modules;
		}
		
	}
?>