<?php

namespace Obcato\Core\elements\photo_album_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\modules\images\model\ImageLabel;

class PhotoAlbumElementMetadataProvider extends ElementMetadataProvider
{

    private MysqlConnector $mysqlConnector;
    private ImageDao $imageDao;
    private Element $element;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->element = $element;
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "photo_album_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void {
        $element->setTitle($record['title']);
        $element->setNumberOfResults($record['number_of_results']);
        $element->setLabels($this->getLabels());
        $element->setImageIds($this->getImageIds($element));
    }

    public function update(Element $element): void {
        $query = "UPDATE photo_album_elements_metadata SET title = ?, ";
        if (!$element->getNumberOfResults()) {
            $query = $query . "number_of_results = NULL ";
        } else {
            $query = $query . "number_of_results = " . $element->getNumberOfResults();
        }
        $query = $query . " WHERE element_id = " . $element->getId();
        $statement = $this->mysqlConnector->prepareStatement($query);
        $title = $element->getTitle();
        $statement->bind_param('s', $title);

        $this->mysqlConnector->executeStatement($statement);
        $this->addLabels();
        $this->addImageIdsIfNotExists($element);
    }

    public function insert(Element $element): void {
        $statement = null;
        $query = "INSERT INTO photo_album_elements_metadata (title, element_id, number_of_results) VALUES
                    (?, ?, NULL)";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $title = $element->getTitle();
        $id = $element->getId();
        $statement->bind_param('si', $title, $id);
        $this->mysqlConnector->executeStatement($statement);
        $this->addLabels();
    }

    private function getLabels(): array {
        $query = "SELECT * FROM photo_album_element_labels WHERE element_id = " . $this->element->getId();
        $result = $this->mysqlConnector->executeQuery($query);
        $labels = array();
        while ($row = $result->fetch_assoc()) {
            array_push($labels, $this->imageDao->getLabel($row['label_id']));
        }
        return $labels;
    }

    private function addLabels(): void {
        $existingLabels = $this->getLabels();
        foreach ($existingLabels as $existingLabel) {
            if (!in_array($existingLabel, $this->element->getLabels()))
                $this->removeLabel($existingLabel);
        }
        foreach ($this->element->getLabels() as $label) {
            if (!in_array($label, $existingLabels)) {
                $statement = $this->mysqlConnector->prepareStatement("INSERT INTO photo_album_element_labels (element_id, label_id) VALUES (?, ?)");
                $labelId = $label->getId();
                $elementId = $this->element->getId();
                $statement->bind_param('ii', $elementId, $labelId);
                $this->mysqlConnector->executeStatement($statement);
            }
        }
    }

    private function removeLabel(ImageLabel $label): void {
        $statement = $this->mysqlConnector->prepareStatement("DELETE FROM photo_album_element_labels WHERE element_id = ? AND label_id = ?");
        $elementId = $this->element->getId();
        $labelId = $label->getId();
        $statement->bind_param('ii', $elementId, $labelId);
        $this->mysqlConnector->executeStatement($statement);
    }

    private function getImageIds(Element $element): array {
        $query = "SELECT * FROM photo_album_element_images WHERE photo_album_element_id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $elementId = $element->getId();
        $statement->bind_param('i', $elementId);
        $result = $this->mysqlConnector->executeStatement($statement);
        $imageIds = array();
        while ($row = $result->fetch_assoc()) {
            $imageIds[] = $row['image_id'];
        }
        return $imageIds;
    }

    private function addImageIdsIfNotExists(Element $element): void {
        $existingImageIds = $this->getImageIds($element);
        foreach ($existingImageIds as $existingImageId) {
            if (!in_array($existingImageId, $this->element->getImageIds()))
                $this->removeImageId($element, $existingImageId);
        }

        $elementId = $element->getId();
        foreach ($this->element->getImageIds() as $imageId) {
            if (!in_array($imageId, $existingImageIds)) {
                $query = "INSERT INTO photo_album_element_images (image_id, photo_album_element_id) VALUES (?, ?)";
                $statement = $this->mysqlConnector->prepareStatement($query);
                $statement->bind_param('ii', $imageId, $elementId);
                $this->mysqlConnector->executeStatement($statement);
            }
        }
    }

    private function removeImageId(Element $element, int $imageId): void {
        $query = "DELETE FROM photo_album_element_images WHERE photo_album_element_id = ? AND image_id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $elementId = $element->getId();
        $statement->bind_param('ii', $elementId, $imageId);
        $this->mysqlConnector->executeStatement($statement);
    }
}