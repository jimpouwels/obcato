<?php

namespace Obcato\Core\elements\text_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;

class TextElementMetadataProvider extends ElementMetadataProvider
{

    private MysqlConnector $mysqlConnector;

    public function __construct(TextElement $textElement)
    {
        parent::__construct($textElement);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "text_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void
    {
        $element->setTitle($record['title']);
        $element->setText($record['text']);
    }

    public function update(Element $element): void
    {
        $title = $this->mysqlConnector->realEscapeString($element->getTitle());
        $text = $this->mysqlConnector->realEscapeString($element->getText());
        $elementId = $element->getId();
        $query = "UPDATE text_elements_metadata SET title = ?, text = ? WHERE element_id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('ssi', $title, $text, $elementId);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function insert(Element $element): void
    {
        $title = $element->getTitle();
        $text = $element->getText();
        $elementId = $element->getId();
        $query = "INSERT INTO text_elements_metadata (title, `text`, element_id) VALUES (?, ?, ?)";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('ssi', $title, $text, $elementId);
        $this->mysqlConnector->executeStatement($statement);
    }

}