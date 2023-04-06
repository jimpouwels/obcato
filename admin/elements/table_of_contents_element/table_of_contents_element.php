<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/visuals/table_of_contents_element_statics.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/visuals/table_of_contents_element_editor.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/table_of_contents_element_request_handler.php";
    require_once CMS_ROOT . "frontend/table_of_contents_element_visual.php";

    class TableOfContentsElement extends Element {
            
        private TableOfContentsElementMetadataProvider $_metadata_provider;
            
        public function __construct() {
            $this->_metadata_provider = new TableOfContentsElementMetadataProvider($this);
        }
        
        public function getStatics() {
            return new TableOfContentsElementStatics();
        }
        
        public function getBackendVisual() {
            return new TableOfContentsElementEditor($this);
        }

        public function getFrontendVisual($current_page) {
            return new TableOfContentsElementFrontendVisual($current_page, $this);
        }
        
        public function initializeMetaData() {
            $this->_metadata_provider->getMetaData($this);
        }
        
        public function updateMetaData() {
            $this->_metadata_provider->updateMetaData($this);
        }

        public function getRequestHandler() {
            return new TableOfContentsElementRequestHandler($this);
        }

        public function getSummaryText() {
            return $this->getTitle();
        }

    }
    
    class TableOfContentsElementMetadataProvider {

        private $_element;

        public function __construct($element) {
            $this->_element = $element;
        }

        public function getMetaData($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT * FROM table_of_contents_elements_metadata " . "WHERE element_id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $element->setTitle($row['title']);
            }
        }

        public function updateMetaData($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            if ($this->metaDataPersisted($element)) {
                $query = "UPDATE table_of_contents_elements_metadata SET title = '" . $element->getTitle() . "'";
            } else {
                $query = "INSERT INTO table_of_contents_elements_metadata (title, element_id) VALUES ('" . $element->getTitle() . "', " . $element->getId() . ")";
            }
            $mysql_database->executeQuery($query);
        }

        private function metaDataPersisted($element) {
            $query = "SELECT t.id, e.id FROM table_of_contents_elements_metadata t, elements e WHERE t.element_id = " 
                    . $element->getId() . " AND e.id = " . $element->getId();
            $mysql_database = MysqlConnector::getInstance(); 
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc())
                return true;
            return false;
        }
        
    }
    
?>