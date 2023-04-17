<?php

    defined("_ACCESS") or die;

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

        abstract function constructMetadata(array $record, Element $element);

        abstract function updateMetaData(Element $element): void;

        abstract function getTableName(): string;
    }
?>