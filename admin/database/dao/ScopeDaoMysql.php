<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/core/model/Scope.php";
require_once CMS_ROOT . "/database/dao/ScopeDao.php";

class ScopeDaoMysql implements ScopeDao {

    private static ?ScopeDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ScopeDaoMysql {
        if (!self::$instance) {
            self::$instance = new ScopeDaoMysql();
        }
        return self::$instance;
    }

    public function getScopes(): array {
        $query = "SELECT * FROM scopes ORDER BY identifier ASC";
        $result = $this->_mysql_connector->executeQuery($query);
        $scopes = array();
        while ($row = $result->fetch_assoc()) {
            $scopes[] = Scope::constructFromRecord($row);
        }
        return $scopes;
    }

    public function getScope(int $id): ?Scope {
        if ($id != "") {
            $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE id = ?");
            $statement->bind_param("i", $id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                return Scope::constructFromRecord($row);
            }
        }
        return null;
    }

    public function getScopeByIdentifier(string $identifier): ?Scope {
        if ($identifier != "") {
            $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM scopes WHERE identifier = ?");
            $statement->bind_param("s", $identifier);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                return Scope::constructFromRecord($row);
            }
        }
        return null;
    }

    public function persistScope(Scope $scope): void {
        $statement = $this->_mysql_connector->prepareStatement("INSERT INTO scopes (identifier) VALUES (?)");
        $identifier = $scope->getIdentifier();
        $statement->bind_param("s", $identifier);
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