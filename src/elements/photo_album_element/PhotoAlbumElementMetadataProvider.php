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

    public function __construct(Element $element)
    {
        parent::__construct($element);
        $this->element = $element;
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "photo_album_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void
    {
        $element->setTitle($record['title']);
        $element->setNumberOfResults($record['number_of_results']);
        $element->setLabels($this->getLabels());
    }

    public function update(Element $element): void
    {
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
    }

    public function insert(Element $element): void
    {
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

    private function getLabels(): array
    {
        $query = "SELECT * FROM photo_album_element_labels WHERE element_id = " . $this->element->getId();
        $result = $this->mysqlConnector->executeQuery($query);
        $labels = array();
        while ($row = $result->fetch_assoc()) {
            array_push($labels, $this->imageDao->getLabel($row['label_id']));
        }
        return $labels;
    }

    private function addLabels(): void
    {
        $existing_labels = $this->getLabels();
        foreach ($existing_labels as $existing_label) {
            if (!in_array($existing_label, $this->element->getLabels()))
                $this->removeLabel($existing_label);
        }
        foreach ($this->element->getLabels() as $label) {
            if (!in_array($label, $existing_labels)) {
                $statement = $this->mysqlConnector->prepareStatement("INSERT INTO photo_album_element_labels (element_id, label_id) VALUES (?, ?)");
                $label_id = $label->getId();
                $element_id = $this->element->getId();
                $statement->bind_param('ii', $element_id, $label_id);
                $this->mysqlConnector->executeStatement($statement);
            }
        }
    }

    private function removeLabel(ImageLabel $label): void
    {
        $statement = $this->mysqlConnector->prepareStatement("DELETE FROM photo_album_element_labels WHERE element_id = ? AND label_id = ?");
        $element_id = $this->element->getId();
        $label_id = $label->getId();
        $statement->bind_param('ii', $element_id, $label_id);
        $this->mysqlConnector->executeStatement($statement);
    }
}