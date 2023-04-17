<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "core/model/scope.php";

    class ScopeDao {

        private static ?ScopeDao $instance = null;
        private MysqlConnector $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance(): ScopeDao {
            if (!self::$instance) {
                self::$instance = new ScopeDao();
            }
            return self::$instance;
        }

        public function getScopes(): array {
            $query = "SELECT * FROM scopes";
            $result = $this->_mysql_connector->executeQuery($query);
            $scope = null;
            $scopes = array();
            while ($row = $result->fetch_assoc()) {
                $scopes[] = Scope::constructFromRecord($row);
            }
            return $scopes;
        }

        public function getScope(int $id): ?Scope {
            if (!is_null($id) && $id != "") {
                $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE id = ?");
                $statement->bind_param("i", $id);
                $result = $this->_mysql_connector->executeStatement($statement);
                while ($row = $result->fetch_assoc()) {
                    return Scope::constructFromRecord($row);
                }
            }
            return null;
        }

        public function getScopeByName(string $name): ?Scope {
            if (!is_null($name) && $name != "") {
                $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE name = ?");
                $statement->bind_param("s", $name);
                $result = $this->_mysql_connector->executeStatement($statement);
                while ($row = $result->fetch_assoc()) {
                    return Scope::constructFromRecord($row);
                }
            }
            return null;
        }

        public function persistScope(Scope $scope): void {
            $statement = $this->_mysql_connector->prepareStatement("INSERT INTO scopes (name) VALUES (?)");
            $scope_name = $scope->getName();
            $statement->bind_param("s", $scope_name);
            $this->_mysql_connector->executeStatement($statement);
            $scope->setId($this->_mysql_connector->getInsertId());
        }

        public function deleteScope(Scope $scope): void {
            $statement = $this->_mysql_connector->prepareStatement('DELETE FROM scopes WHERE id = ?');
            $scope_id = $scope->getId();
            $statement->bind_param('s', $scope_id);
            $this->_mysql_connector->executeStatement($statement);
        }
    }
?>