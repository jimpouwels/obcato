<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/ElementDao.php";
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/core/model/ElementType.php";
require_once CMS_ROOT . "/core/model/Element.php";

class ElementDaoMysql implements ElementDao {

    private static string $myAllColumns = "e.id, e.follow_up, e.template_id, e.include_in_table_of_contents, t.classname, t.scope_id, t.identifier, 
                                        t.domain_object, e.element_holder_id";
    private static ?ElementDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ElementDaoMysql {
        if (!self::$instance) {
            self::$instance = new ElementDaoMysql();
        }
        return self::$instance;
    }

    public function getElements(ElementHolder $element_holder): array {
        $elements_info_query = "SELECT " . self::$myAllColumns . " FROM elements e, element_types t WHERE element_holder_id 
                                    = " . $element_holder->getId() . " AND t.id = e.type_id ORDER BY e.follow_up ASC, e.id";
        $result = $this->_mysql_connector->executeQuery($elements_info_query);
        $elements = array();
        while ($row = $result->fetch_assoc()) {
            $elements[] = Element::constructFromRecord($row);
        }
        return $elements;
    }

    public function getElement(int $id): ?Element {
        $query = "SELECT " . self::$myAllColumns . " FROM elements e, element_types t WHERE e.id = " . $id . " 
                      AND e.type_id = t.id;";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Element::constructFromRecord($row);
        }
        return null;
    }

    public function updateElement(Element $element): void {
        $set = 'follow_up = ' . $element->getOrderNr();
        if (!is_null($element->getTemplateId()) && $element->getTemplateId() != '') {
            $set .= ', template_id = ' . $element->getTemplateId();
        } else {
            $set .= ', template_id = NULL';
        }
        $set .= ', include_in_table_of_contents = ' . ($element->includeInTableOfContents() ? '1' : '0');
        $query = "UPDATE elements SET " . $set . "    WHERE id = " . $element->getId();
        $this->_mysql_connector->executeQuery($query);
        $element->updateMetaData();
    }

    public function deleteElement(Element $element): void {
        $statement = $this->_mysql_connector->prepareStatement("DELETE FROM elements WHERE id = ?");
        $element_id = $element->getId();
        $statement->bind_param('i', $element_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getElementTypes(): array {
        $query = "SELECT * FROM element_types ORDER BY identifier";
        $result = $this->_mysql_connector->executeQuery($query);
        $element_types = array();
        while ($row = $result->fetch_assoc()) {
            $element_types[] = ElementType::constructFromRecord($row);
        }
        return $element_types;
    }

    public function updateElementType(ElementType $element_type): void {
        $system_default_val = 0;
        if ($element_type->getSystemDefault()) {
            $system_default_val = 1;
        }
        $query = "UPDATE element_types SET classname = '" . $element_type->getClassName() . "', icon_url = '" . $element_type->getIconUrl() . "', domain_object = '" . $element_type->getDomainObject() . "', scope_id = " .
            $element_type->getScopeId() . ", identifier = '" . $element_type->getIdentifier() . "', system_default = " .
            $system_default_val . " WHERE identifier = '" . $element_type->getIdentifier() . "'";
        $this->_mysql_connector->executeQuery($query);
    }

    public function persistElementType(ElementType $element_type): void {
        $system_default_val = 0;
        if ($element_type->getSystemDefault()) {
            $system_default_val = 1;
        }
        $query = "INSERT INTO element_types (classname, icon_url, domain_object, scope_id, identifier, system_default)" .
            " VALUES ('" . $element_type->getClassName() . "', '" . $element_type->getIconUrl() .
            "', '" . $element_type->getDomainObject() . "', " . $element_type->getScopeId() . ", " .
            "'" . $element_type->getIdentifier() . "', " . $system_default_val . ")";
        $this->_mysql_connector->executeQuery($query);
        $element_type->setId($this->_mysql_connector->getInsertId());
    }

    public function deleteElementType(ElementType $element_type): void {
        $query = "DELETE FROM element_types WHERE id = " . $element_type->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function getDefaultElementTypes(): array {
        $query = "SELECT * FROM element_types WHERE system_default = 1 ORDER BY identifier";
        $result = $this->_mysql_connector->executeQuery($query);
        $element_types = array();
        while ($row = $result->fetch_assoc())
            $element_types[] = ElementType::constructFromRecord($row);
        return $element_types;
    }

    public function getCustomElementTypes(): array {
        $query = "SELECT * FROM element_types WHERE system_default = 0 ORDER BY identifier";
        $result = $this->_mysql_connector->executeQuery($query);
        $element_types = array();
        while ($row = $result->fetch_assoc()) {
            $element_types[] = ElementType::constructFromRecord($row);
        }
        return $element_types;
    }

    public function getElementType(int $element_type_id): ?ElementType {
        $query = "SELECT * FROM element_types WHERE id = " . $element_type_id;
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ElementType::constructFromRecord($row);
        }
        return null;
    }

    public function getElementTypeByIdentifier(string $identifier): ?ElementType {
        $query = "SELECT * FROM element_types WHERE identifier = '$identifier'";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ElementType::constructFromRecord($row);
        }
        return null;
    }

    public function getElementTypeForElement(int $element_id): ?ElementType {
        $query = "SELECT * FROM element_types t, elements e WHERE e.id = " . $element_id . " AND t.id = e.type_id";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ElementType::constructFromRecord($row);
        }
        return null;
    }

    public function createElement(ElementType $element_type, int $element_holder_id): Element {
        require_once CMS_ROOT . "/elements/" . $element_type->getIdentifier() . "/" . $element_type->getDomainObject();
        $element_classname = $element_type->getClassName();
        $new_element = new $element_classname($element_type->getScopeId());
        $new_element->setOrderNr(999);
        $this->persistElement($element_type, $new_element, $element_holder_id);
        return $new_element;
    }

    private function persistElement(ElementType $element_type, Element $element, int $element_holder_id): void {
        $query = "INSERT INTO elements(follow_up, type_id, element_holder_id) VALUES (" . $element->getOrderNr() . " 
                      , " . $element_type->getId() . ", " . $element_holder_id . ")";
        $this->_mysql_connector->executeQuery($query);
        $element->setId($this->_mysql_connector->getInsertId());
        $element->updateMetaData();
    }

}

?>