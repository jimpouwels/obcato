<?php

namespace Obcato\Core\elements\image_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;

class ImageElementMetadataProvider extends ElementMetadataProvider
{

    private MysqlConnector $_mysql_connector;

    public function __construct(Element $element)
    {
        parent::__construct($element);
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "image_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void
    {
        $element->setTitle($record['title']);
        $element->setAlign($record['align']);
        $element->setImageId($record['image_id']);
        $element->setWidth($record['width']);
        $element->setHeight($record['height']);
    }

    public function update(Element $element): void
    {
        $image_id = $element->getImageId();
        $title = $element->getTitle();
        $align = $element->getAlign();
        $width = $element->getWidth();
        $height = $element->getHeight();
        $element_id = $element->getId();
        $query = "UPDATE image_elements_metadata SET title = ?, align = ?, image_id = ?, width = ?, height = ? WHERE element_id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('ssiiii', $title, $align, $image_id, $width, $height, $element_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function insert(Element $element): void
    {
        $title = $element->getTitle();
        $element_id = $element->getId();
        $query = "INSERT INTO image_elements_metadata (title, align, width, height, image_id, element_id) VALUES (?, NULL, 0 , 0, NULL, ?)";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('si', $title, $element_id);
        $this->_mysql_connector->executeStatement($statement);
    }

}