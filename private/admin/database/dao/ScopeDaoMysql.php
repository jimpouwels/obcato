<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/templates/model/Scope.php";
require_once CMS_ROOT . "/database/dao/ScopeDao.php";

class ScopeDaoMysql implements ScopeDao {

    private static ?ScopeDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ScopeDaoMysql {
        if (!self::$instance) {
            self::$instance = new ScopeDaoMysql();
        }
        return self::$instance;
    }

    public function getScopes(): array {
        $query = "SELECT * FROM scopes ORDER BY identifier ASC";
        $result = $this->mysqlConnector->executeQuery($query);
        $scopes = array();
        while ($row = $result->fetch_assoc()) {
            $scopes[] = Scope::constructFromRecord($row);
        }
        return $scopes;
    }

    public function getScope(int $id): ?Scope {
        $statement = $this->mysqlConnector->prepareStatement("SELECT * FROM scopes WHERE id = ?");
        $statement->bind_param("i", $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Scope::constructFromRecord($row);
        }
        return null;
    }

    public function getScopeByIdentifier(string $identifier): ?Scope {
        if ($identifier != "") {
            $statement = $this->mysqlConnector->prepareStatement("SELECT * FROM scopes WHERE identifier = ?");
            $statement->bind_param("s", $identifier);
            $result = $this->mysqlConnector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                return Scope::constructFromRecord($row);
            }
        }
        return null;
    }

    public function persistScope(Scope $scope): void {
        $statement = $this->mysqlConnector->prepareStatement("INSERT INTO scopes (identifier) VALUES (?)");
        $identifier = $scope->getIdentifier();
        $statement->bind_param("s", $identifier);
        $this->mysqlConnector->executeStatement($statement);
        $scope->setId($this->mysqlConnector->getInsertId());
    }

    public function deleteScope(Scope $scope): void {
        $statement = $this->mysqlConnector->prepareStatement('DELETE FROM scopes WHERE id = ?');
        $scopeId = $scope->getId();
        $statement->bind_param('s', $scopeId);
        $this->mysqlConnector->executeStatement($statement);
    }
}

?>