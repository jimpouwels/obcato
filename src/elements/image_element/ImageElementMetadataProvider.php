<?php

namespace Obcato\Core\elements\image_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;

class ImageElementMetadataProvider extends ElementMetadataProvider
{

    private MysqlConnector $mysqlConnector;

    public function __construct(Element $element)
    {
        parent::__construct($element);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "image_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void {
        $element->setTitle($record['title']);
        $element->setUrl($record['url']);
        $element->setAlign($record['align']);
        $element->setImageId($record['image_id']);
        $element->setWidth($record['width']);
        $element->setHeight($record['height']);
        $element->setLinkId($record['link_id']);
    }

    public function update(Element $element): void
    {
        $imageId = $element->getImageId();
        $title = $element->getTitle();
        $align = $element->getAlign();
        $url = $element->getUrl();
        $width = $element->getWidth();
        $height = $element->getHeight();
        $elementId = $element->getId();
        $linkId = $element->getLinkId();
        $query = "UPDATE image_elements_metadata SET title = ?, align = ?, image_id = ?, width = ?, height = ?, link_id = ?, url = ? WHERE element_id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('ssiiiisi', $title, $align, $imageId, $width, $height, $linkId, $url, $elementId);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function insert(Element $element): void
    {
        $title = $element->getTitle();
        $element_id = $element->getId();
        $query = "INSERT INTO image_elements_metadata (title, align, width, height, image_id, element_id) VALUES (?, NULL, 0 , 0, NULL, ?)";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('si', $title, $element_id);
        $this->mysqlConnector->executeStatement($statement);
    }

}