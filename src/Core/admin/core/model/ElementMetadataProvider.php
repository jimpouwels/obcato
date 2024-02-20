<?php

namespace Obcato\Core\admin\core\model;

use Obcato\Core\MysqlConnector;

abstract class ElementMetadataProvider {

    private Element $_element;
    private MysqlConnector $_mysql_connector;

    public function __construct(Element $element) {
        $this->_element = $element;
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getElement(): Element {
        return $this->_element;
    }

    public function loadMetaData(): void {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE element_id = " . $this->_element->getId();
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $this->constructMetadata($row, $this->_element);
        }
    }

    abstract function getTableName(): string;

    abstract function constructMetadata(array $record, Element $element);

    public function upsert(Element $element): void {
        if (!$this->isPersisted($element)) {
            $this->insert($element);
        } else {
            $this->update($element);
        }
    }

    private function isPersisted(Element $element): bool {
        $mysql_database = MysqlConnector::getInstance();
        $statement = $this->_mysql_connector->prepareStatement("SELECT t.id, e.id FROM {$this->getTableName()} t, elements e WHERE t.element_id = ? AND e.id = ?");
        $element_id = $element->getId();
        $statement->bind_param("ii", $element_id, $element_id);
        $result = $mysql_database->executeStatement($statement);
        while ($result->fetch_assoc()) {
            return true;
        }
        return false;
    }

    abstract function insert(Element $element): void;

    abstract function update(Element $element): void;
}