<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\database\MysqlConnector;

class ElementHolderDaoMysql implements ElementHolderDao {

    private static string $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id,
                      e.created_at, e.created_by, e.type, e.last_modified";

    private static ?ElementHolderDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;
    private AuthorizationDao $authorizationDao;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
        $this->authorizationDao = AuthorizationDaoMysql::getInstance();
    }

    public static function getInstance(): ElementHolderDaoMysql {
        if (!self::$instance) {
            self::$instance = new ElementHolderDaoMysql();
        }
        return self::$instance;
    }

    public function getElementHolder(int $id): ?ElementHolder {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e WHERE e.id = " . $id;
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ElementHolder::constructFromRecord($row);
        }
        return null;
    }

    public function persist(ElementHolder $elementHolder): void {
        $query = "INSERT INTO element_holders (template_id, title, published, scope_id, created_at, created_by, type) VALUES
                      (NULL, '" . $elementHolder->getTitle() . "', 0," . $elementHolder->getScopeId() . ", now(), " . $this->authorizationDao->getUserById($elementHolder->getCreatedById())->getId() . "
                      , '" . $elementHolder->getType() . "')";
        $this->mysqlConnector->executeQuery($query);
        $elementHolder->setId($this->mysqlConnector->getInsertId());
    }

    public function update(ElementHolder $elementHolder): void {
        $query = "UPDATE element_holders SET title = '" . $this->mysqlConnector->realEscapeString($elementHolder->getTitle()) . "', published = " . ($elementHolder->isPublished() ? 1 : 0) . ",
                      scope_id = " . $elementHolder->getScopeId() . ", template_id = " . ($elementHolder->getTemplateId() ? $elementHolder->getTemplateId() : "NULL") . ", last_modified = NOW()";
        $query .= " WHERE id = " . $elementHolder->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function delete(ElementHolder $elementHolder): void {
        $query = "DELETE FROM element_holders WHERE id = " . $elementHolder->getId();
        $this->mysqlConnector->executeQuery($query);
    }
}