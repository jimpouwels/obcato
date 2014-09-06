<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "/database/mysql_connector.php";
	include_once CMS_ROOT . "/core/data/scope.php";

	/*
		This class takes care of all persistance actions for a Template object.
	*/
	class ScopeDao {
	
		/*
			This service is a singleton
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/* 
			Creates a new instance (if not yet exists
			for this DAO
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ScopeDao();
			}
			return self::$instance;
		}
		
		/*
			Returns all scopes from the database.
		*/
		public function getScopes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM scopes";
			$result = $mysql_database->executeSelectQuery($query);
			
			$scope = NULL;
			$scopes = array();
			while ($row = mysql_fetch_assoc($result)) {
				$scope = Scope::constructFromRecord($row);
				
				array_push($scopes, $scope);
			}
			
			return $scopes;
		}
		
		/*
			Returns the Scope object for the given ID.
			
			@param $id The ID of the scope to find
		*/
		public function getScope($id) {
			$scope = NULL;
			if (!is_null($id) && $id != '') {
				$mysql_database = MysqlConnector::getInstance(); 
			
				$query = "SELECT * FROM scopes WHERE id = " . $id;
				$result = $mysql_database->executeSelectQuery($query);
				
				while ($row = mysql_fetch_assoc($result)) {
					$scope = Scope::constructFromRecord($row);
					
					break;
				}
			}
			
			return $scope;
		}
		
		/*
			Returns the Scope object for the given name.
			
			@param $name The name of the scope to find
		*/
		public function getScopeByName($name) {
			$scope = NULL;
			if (!is_null($name) && $name != '') {
				$mysql_database = MysqlConnector::getInstance(); 
			
				$query = "SELECT * FROM scopes WHERE name = '$name'";
				$result = $mysql_database->executeSelectQuery($query);
				
				while ($row = mysql_fetch_assoc($result)) {
					$scope = Scope::constructFromRecord($row);
					
					break;
				}
			}
			
			return $scope;
		}
		
		/*
			Persists a new scope.
			
			@param $scope The scope to be persisted
		*/
		public function persistScope($scope) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO scopes (name) VALUES ('" . $scope->getName() . "')";

			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
	}
?>