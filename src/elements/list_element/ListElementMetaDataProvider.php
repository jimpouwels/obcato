<?php

namespace Obcato\Core\elements\list_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;

class ListElementMetaDataProvider extends ElementMetadataProvider
{

    private static string $myAllColumns = "i.id, i.text, i.indent, i.element_id";
    private MysqlConnector $mysqlConnector;

    public function __construct(Element $element)
    {
        parent::__construct($element);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "list_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void
    {
        $element->setTitle($record['title']);
        $element->setListItems($this->getListItems($element));
    }

    public function deleteListItem(ListItem $list_item): void
    {
        $query = "DELETE FROM list_element_items WHERE id = " . $list_item->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function update(Element $element): void
    {
        $query = "UPDATE list_elements_metadata SET title = '" . $element->getTitle() . "' WHERE element_id = " . $element->getId();
        $this->mysqlConnector->executeQuery($query);

        $this->storeItems($element->getListItems());
    }

    public function insert(Element $element): void
    {
        $query = "INSERT INTO list_elements_metadata (title, element_id) VALUES 
                        ('" . $element->getTitle() . "', " . $element->getId() . ")";
        $this->mysqlConnector->executeQuery($query);
    }

    private function storeItems(array $items): void
    {
        foreach ($items as $listItem) {
            if ($listItem->getId()) {
                $this->updateListItem($listItem);
            } else {
                $this->insertListItem($listItem);
            }
        }
    }

    private function getListItems(Element $element): array
    {
        $query = "SELECT " . self::$myAllColumns . " FROM list_element_items i, elements e WHERE i.element_id = " . $element->getId() .
            " AND e.id = " . $element->getId();
        $result = $this->mysqlConnector->executeQuery($query);
        $listItems = array();
        while ($row = $result->fetch_assoc()) {
            $listItem = new ListItem();
            $listItem->setId($row['id']);
            $listItem->setText($row['text']);
            $listItem->setIndent($row['indent']);
            $listItem->setElementId($row['element_id']);

            $listItems[] = $listItem;
        }

        return $listItems;
    }

    private function updateListItem(mixed $listItem): void
    {
        $query = "UPDATE list_element_items SET `text` = ?, indent = ? WHERE id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $indent = $listItem->getIndent();
        $id = $listItem->getId();
        $text = $listItem->getText();
        $statement->bind_param('sii', $text, $indent, $id);
        $this->mysqlConnector->executeStatement($statement);
    }

    private function insertListItem(mixed $listItem): void
    {
        $query = "INSERT INTO list_element_items (`text`, indent, element_id) VALUES ('', ?, ?)";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $indent = $listItem->getIndent();
        $elementId = $this->getElement()->getId();
        $statement->bind_param('ii', $indent, $elementId);
        $this->mysqlConnector->executeStatement($statement);
        $listItem->setId($this->mysqlConnector->getInsertId());
    }

}