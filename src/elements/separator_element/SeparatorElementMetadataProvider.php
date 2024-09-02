<?php

namespace Obcato\Core\elements\separator_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;

class SeparatorElementMetadataProvider extends ElementMetadataProvider
{

    private MysqlConnector $mysqlConnector;

    public function __construct(Element $element)
    {
        parent::__construct($element);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "separator_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void
    {
        $element->setTitle($record['title']);
    }

    public function update(Element $element): void
    {
        $query = "UPDATE separator_elements_metadata SET title = '" . $element->getTitle() . "' WHERE element_id = " . $element->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function insert(Element $element): void
    {
        $query = "INSERT INTO separator_elements_metadata (title, element_id) VALUES ('" . $element->getTitle() . "', " . $element->getId() . ")";
        $this->mysqlConnector->executeQuery($query);
    }

}