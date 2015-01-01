<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "core/model/scope.php";

    class ScopeDao {

        private static $instance;
        private $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance)
                self::$instance = new ScopeDao();
            return self::$instance;
        }

        public function getScopes() {
            $query = "SELECT * FROM scopes";
            $result = $this->_mysql_connector->executeQuery($query);
            $scope = null;
            $scopes = array();
            while ($row = $result->fetch_assoc())
                $scopes[] = Scope::constructFromRecord($row);
            return $scopes;
        }

        public function getScope($id) {
            if (!is_null($id) && $id != "") {
                $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE id = ?");
                $statement->bind_param("i", $id);
                $result = $this->_mysql_connector->executeStatement($statement);
                while ($row = $result->fetch_assoc())
                    return Scope::constructFromRecord($row);
            }
        }

        public function getScopeByName($name) {
            if (!is_null($name) && $name != "") {
                $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE name = ?");
                $statement->bind_param("s", $name);
                $result = $this->_mysql_connector->executeStatement($statement);
                while ($row = $result->fetch_assoc())
                    return Scope::constructFromRecord($row);
            }
        }

        public function persistScope($scope) {
            $statement = $this->_mysql_connector->prepareStatement("INSERT INTO scopes (name) VALUES (?)");
            $scope_name = $scope->getName();
            $statement->bind_param("s", $scope_name);
            $this->_mysql_connector->executeStatement($statement);
            $scope->setId($this->_mysql_connector->getInsertId());
        }

        public function deleteScope($scope) {
            $statement = $this->_mysql_connector->prepareStatement('DELETE FROM scopes WHERE id = ?');
            $scope_id = $scope->getId();
            $statement->bind_param('s', $scope_id);
            $this->_mysql_connector->executeStatement($statement);
        }
    }
?>