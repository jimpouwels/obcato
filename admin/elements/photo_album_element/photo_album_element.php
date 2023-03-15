<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "elements/photo_album_element/visuals/photo_album_element_statics.php";
    require_once CMS_ROOT . "elements/photo_album_element/visuals/photo_album_element_editor.php";
    require_once CMS_ROOT . "elements/photo_album_element/photo_album_element_request_handler.php";
    require_once CMS_ROOT . "frontend/photo_album_element_visual.php";

    class PhotoAlbumElement extends Element {
            
        private $_title;
        private $_labels;
        private $_number_of_results;
        private $_metadata_provider;
            
        public function __construct() {
            $this->_labels = array();
            $this->_metadata_provider = new PhotoAlbumElementMetaDataProvider($this);
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function getTitle() {
            return $this->_title;
        }
        
        public function setNumberOfResults($number_of_results) {
            $this->_number_of_results = $number_of_results;
        }
        
        public function getNumberOfResults() {
            return $this->_number_of_results;
        }

        public function addLabel($label) {
            $this->_labels[] = $label;
        }

        public function removeLabel($label) {
            if(($key = array_search($label, $this->_labels, true)) !== false)
                unset($this->_labels[$key]);
        }

        public function setLabels($labels) {
            $this->_labels = $labels;
        }
        
        public function getLabels() {
            return $this->_labels;
        }
        
        public function getImages() {
            include_once CMS_ROOT . "database/dao/image_dao.php";
            $image_dao = ImageDao::getInstance();
            $images = $image_dao->searchImagesByLabels($this->_labels);
            return $images;
        }
        
        public function getStatics() {
            return new PhotoAlbumElementStatics();
        }
        
        public function getBackendVisual() {
            return new PhotoAlbumElementEditor($this);
        }

        public function getFrontendVisual($current_page) {
            return new PhotoAlbumElementFrontendVisual($current_page, $this);
        }
        
        public function initializeMetaData() {
            $this->_metadata_provider->getMetaData($this);
        }
        
        public function updateMetaData() {
            $this->_metadata_provider->updateMetaData($this);
        }

        public function getRequestHandler() {
            return new PhotoAlbumElementRequestHandler($this);
        }
    }
    
    class PhotoAlbumElementMetaDataProvider {

        private $_image_dao;
        private $_element;

        public function __construct($element) {
            $this->_element = $element;
            $this->_image_dao = ImageDao::getInstance();
        }

        public function getMetaData($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT * FROM photo_album_elements_metadata " . "WHERE element_id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $element->setTitle($row['title']);
                $element->setNumberOfResults($row['number_of_results']);
            }
            $element->setLabels($this->getLabels());
        }

        private function getLabels() {
            $mysql_database = MysqlConnector::getInstance(); 
            $query = "SELECT * FROM photo_album_element_labels WHERE element_id = " . $this->_element->getId();
            $result = $mysql_database->executeQuery($query);
            $labels = array();
            while ($row = $result->fetch_assoc()) {
                array_push($labels, $this->_image_dao->getLabel($row['label_id']));
            }
            return $labels;
        }

        public function updateMetaData($element) {
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

        private function addLabels() {
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

        private function removeLabel($label) {
            $mysql_database = MysqlConnector::getInstance();
            $statement = $mysql_database->prepareStatement("DELETE FROM photo_album_element_labels WHERE element_id = ? AND label_id = ?");
            $element_id = $this->_element->getId();
            $label_id = $label->getId();
            $statement->bind_param('ii', $element_id, $label_id);
            $mysql_database->executeStatement($statement);
        }

        private function metaDataPersisted($element) {
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