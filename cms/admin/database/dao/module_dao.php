<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "/database/mysql_connector.php";
	include_once CMS_ROOT . "/core/data/module_group.php";
	include_once CMS_ROOT . "/core/data/module.php";

	class ModuleDao {

		private static $instance;

		private function __construct() {
		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ModuleDao();
			}
			return self::$instance;
		}

		public function getDefaultModules() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE system_default = 1 ORDER BY title";
			$result = $mysql_database->executeQuery($query);
			$modules = array();
			while ($row = $result->fetch_assoc()) {
				$module = Module::constructFromRecord($row);
				array_push($modules, $module);
			}
			
			return $modules;
		}

		public function getCustomModules() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE system_default = 0 ORDER by title";
			$result = $mysql_database->executeQuery($query);
			$modules = array();
			while ($row = $result->fetch_assoc()) {
				$module = Module::constructFromRecord($row);
				array_push($modules, $module);
			}
			
			return $modules;
		}

		public function getModule($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE id = " . $id;
			$result = $mysql_database->executeQuery($query);
			
			$module = NULL;
			while ($row = $result->fetch_assoc()) {
				$module = Module::constructFromRecord($row);
				
				break;
			}
			return $module;
		}

		public function getModuleByIdentifier($identifier) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE identifier = '" . $identifier . "'";
			$result = $mysql_database->executeQuery($query);
			
			$module = NULL;
			while ($row = $result->fetch_assoc()) {
				$module = Module::constructFromRecord($row);
				break;
			}
			return $module;
		}

		public function getModuleGroups() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM module_groups ORDER BY follow_up";
			$result = $mysql_database->executeQuery($query);
			$groups = array();
			
			while ($row = $result->fetch_assoc()) {
				$module_group = ModuleGroup::constructFromRecord($row);
				array_push($groups, $module_group);
			}
			
			return $groups;
		}

		public function getHomeModules() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM modules WHERE home_item = 1";
			$result = $mysql_database->executeQuery($query);
			$modules = array();
			while ($row = $result->fetch_assoc()) {
				$module = Module::constructFromRecord($row);
				array_push($modules, $module);
			}
			
			return $modules;
		}
		
	}
?>