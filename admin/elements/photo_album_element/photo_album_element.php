<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "core/model/element_metadata_provider.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "elements/photo_album_element/visuals/photo_album_element_statics.php";
    require_once CMS_ROOT . "elements/photo_album_element/visuals/photo_album_element_editor.php";
    require_once CMS_ROOT . "elements/photo_album_element/photo_album_element_request_handler.php";
    require_once CMS_ROOT . "frontend/photo_album_element_visual.php";

    class PhotoAlbumElement extends Element {
            
        private array $_labels;
        private ?int $_number_of_results = null;
            
        public function __construct() {
            parent::__construct(new PhotoAlbumElementMetadataProvider($this));
            $this->_labels = array();
        }
        
        public function setNumberOfResults(?int $number_of_results): void {
            $this->_number_of_results = $number_of_results;
        }
        
        public function getNumberOfResults(): ?int {
            return $this->_number_of_results;
        }

        public function addLabel(ImageLabel $label): void {
            $this->_labels[] = $label;
        }

        public function removeLabel(ImageLabel $label): void {
            if(($key = array_search($label, $this->_labels, true)) !== false) {
                unset($this->_labels[$key]);
            }
        }

        public function setLabels(array $labels): void {
            $this->_labels = $labels;
        }
        
        public function getLabels(): array {
            return $this->_labels;
        }
        
        public function getImages(): array {
            $image_dao = ImageDao::getInstance();
            $images = $image_dao->searchImagesByLabels($this->_labels);
            return $images;
        }
        
        public function getStatics(): Visual {
            return new PhotoAlbumElementStatics();
        }
        
        public function getBackendVisual(): ElementVisual {
            return new PhotoAlbumElementEditor($this);
        }

        public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
            return new PhotoAlbumElementFrontendVisual($page, $article, $this);
        }
        
        public function getRequestHandler(): HttpRequestHandler {
            return new PhotoAlbumElementRequestHandler($this);
        }
        
        public function getSummaryText(): string {
            $summary_text = $this->getTitle();
            if ($this->getLabels()) {
                $summary_text .= " (Labels:";
                foreach ($this->getLabels() as $label) {
                    $summary_text .= " " . $label->getName();
                }
                $summary_text .= ")";
            }
            return $summary_text;
        }
    }
    
    class PhotoAlbumElementMetadataProvider extends ElementMetadataProvider {

        private ImageDao $_image_dao;
        private Element $_element;

        public function __construct(Element $element) {
            parent::__construct($element);
            $this->_element = $element;
            $this->_image_dao = ImageDao::getInstance();
        }

        public function getTableName(): string {
            return "photo_album_elements_metadata";
        }

        public function constructMetaData(array $row, $element): void {
            $element->setTitle($row['title']);
            $element->setNumberOfResults($row['number_of_results']);
            $element->setLabels($this->getLabels());
        }

        public function updateMetaData(Element $element): void {
            $mysql_database = MysqlConnector::getInstance(); 
            
            if ($this->metaDataPersisted($element)) {
                $query = "UPDATE photo_album_elements_metadata SET title = '" . $element->getTitle() . "', ";
                if (is_null($element->getNumberOfResults()) || $element->getNumberOfResults() == '') {
                    $query = $query . "number_of_results = NULL ";
                } else {
                    $query = $query . "number_of_results = " . $element->getNumberOfResults() . "";
                }
                $query = $query . " WHERE element_id = " . $element->getId();
            } else {
                $query = "INSERT INTO photo_album_elements_metadata (title, element_id, number_of_results) VALUES
                          ('" . $element->getTitle() . "', " . $element->getId() . ", NULL)";
            }
            $mysql_database->executeQuery($query);
            $this->addLabels();
        }

        private function getLabels(): array {
            $mysql_database = MysqlConnector::getInstance(); 
            $query = "SELECT * FROM photo_album_element_labels WHERE element_id = " . $this->_element->getId();
            $result = $mysql_database->executeQuery($query);
            $labels = array();
            while ($row = $result->fetch_assoc()) {
                array_push($labels, $this->_image_dao->getLabel($row['label_id']));
            }
            return $labels;
        }

        private function addLabels(): void {
            $existing_labels = $this->getLabels();
            foreach ($existing_labels as $existing_label) {
                if (!in_array($existing_label, $this->_element->getLabels()))
                    $this->removeLabel($existing_label);
            }
            foreach ($this->_element->getLabels() as $label) {
                if (!in_array($label, $existing_labels)) {
                    $mysql_database = MysqlConnector::getInstance();
                    $statement = $mysql_database->prepareStatement("INSERT INTO photo_album_element_labels (element_id, label_id) VALUES (?, ?)");
                    $label_id = $label->getId();
                    $element_id = $this->_element->getId();
                    $statement->bind_param('ii', $element_id, $label_id);
                    $mysql_database->executeStatement($statement);
                }
            }
        }

        private function removeLabel(ImageLabel $label): void {
            $mysql_database = MysqlConnector::getInstance();
            $statement = $mysql_database->prepareStatement("DELETE FROM photo_album_element_labels WHERE element_id = ? AND label_id = ?");
            $element_id = $this->_element->getId();
            $label_id = $label->getId();
            $statement->bind_param('ii', $element_id, $label_id);
            $mysql_database->executeStatement($statement);
        }

        private function metaDataPersisted(Element $element): bool {
            $query = "SELECT t.id, e.id FROM photo_album_elements_metadata t, elements e WHERE t.element_id = " 
                    . $element->getId() . " AND e.id = " . $element->getId();
            $mysql_database = MysqlConnector::getInstance(); 
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc())
                return true;
            return false;
        }
        
    }
    
?>