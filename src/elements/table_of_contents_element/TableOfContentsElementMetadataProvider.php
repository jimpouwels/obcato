<?php

namespace Obcato\Core\elements\table_of_contents_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;

class TableOfContentsElementMetadataProvider extends ElementMetadataProvider
{

    private MysqlConnector $mysqlConnector;

    public function __construct(TableOfContentsElement $element)
    {
        parent::__construct($element);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "table_of_contents_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void
    {
        $element->setTitle($record['title']);
    }

    public function update(Element $element): void
    {
        $query = "UPDATE table_of_contents_elements_metadata SET title = '" . $element->getTitle() . "'";
        $this->mysqlConnector->executeQuery($query);
    }

    public function insert(Element $element): void
    {
        $query = "INSERT INTO table_of_contents_elements_metadata (title, element_id) VALUES ('" . $element->getTitle() . "', " . $element->getId() . ")";
        $this->mysqlConnector->executeQuery($query);
    }

}