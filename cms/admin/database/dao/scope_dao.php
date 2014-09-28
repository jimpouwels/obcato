<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "/database/mysql_connector.php";
    require_once CMS_ROOT . "/core/data/scope.php";

	class ScopeDao {

		private static $instance;
        private $_mysql_connector;

		private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ScopeDao();
			}
			return self::$instance;
		}

		public function getScopes() {
			$query = "SELECT * FROM scopes";
			$result = $this->_mysql_connector->executeQuery($query);
			$scope = null;
			$scopes = array();
			while ($row = $result->fetch_assoc()) {
				$scope = Scope::constructFromRecord($row);
				array_push($scopes, $scope);
			}
			return $scopes;
		}

		public function getScope($id) {
			$scope = null;
			if (!is_null($id) && $id != "") {
				$statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE id = ?");
                $statement->bind_param("i", $id);
				$result = $this->_mysql_connector->executeStatement($statement);
				while ($row = $result->fetch_assoc()) {
					$scope = Scope::constructFromRecord($row);
					break;
				}
			}
			return $scope;
		}

		public function getScopeByName($name) {
			$scope = null;
			if (!is_null($name) && $name != "") {
				$statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE name = ?");
                $statement->bind_param("s", $name);
				$result = $this->_mysql_connector->executeStatement($statement);
				while ($row = $result->fetch_assoc()) {
					$scope = Scope::constructFromRecord($row);
					break;
				}
			}
			
			return $scope;
		}

		public function persistScope($scope) {
			$statement = $this->_mysql_connector->prepareStatement("INSERT INTO scopes (name) VALUES (?)");
            $statement->bind_param("s", $scope->getName());
            $this->_mysql_connector->executeStatement($statement);
			return $this->_mysql_connector->getInsertId();
		}
	}
?>