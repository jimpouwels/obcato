<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/ElementHolderDao.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/core/model/ElementHolder.php";

class ElementHolderDaoMysql implements ElementHolderDao {

    private static string $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id,
                      e.created_at, e.created_by, e.type, e.last_modified";

    private static ?ElementHolderDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;
    private AuthorizationDao $authorizationDao;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
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
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ElementHolder::constructFromRecord($row);
        }
        return null;
    }

    public function persist(ElementHolder $element_holder): void {
        $query = "INSERT INTO element_holders (template_id, title, published, scope_id, created_at, created_by, type) VALUES
                      (NULL, '" . $element_holder->getTitle() . "', 0," . $element_holder->getScopeId() . ", now(), " . $this->authorizationDao->getUserById($element_holder->getCreatedById()) . "
                      , '" . $element_holder->getType() . "')";
        $this->_mysql_connector->executeQuery($query);
        $element_holder->setId($this->_mysql_connector->getInsertId());
    }

    public function update(ElementHolder $element_holder): void {
        $published_value = ($element_holder->isPublished()) ? 1 : 0;
        $query = "UPDATE element_holders SET title = '" . $this->_mysql_connector->realEscapeString($element_holder->getTitle()) . "', published = " . $published_value . ",
                      scope_id = " . $element_holder->getScopeId() . ", template_id = " . ($element_holder->getTemplateId() ? $element_holder->getTemplateId() : "NULL") . ", last_modified = NOW()";
        $query .= " WHERE id = " . $element_holder->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function delete(ElementHolder $element_holder): void {
        $query = "DELETE FROM element_holders WHERE id = " . $element_holder->getId();
        $this->_mysql_connector->executeQuery($query);
    }
}

?>