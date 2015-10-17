<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "elements/text_element/visuals/text_element_editor.php";
    require_once CMS_ROOT . "elements/text_element/visuals/text_element_statics.php";
    require_once CMS_ROOT . "elements/text_element/text_element_request_handler.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "frontend/text_element_visual.php";

    class TextElement extends Element {
            
        private $_title;
        private $_text;
            
        public function __construct() {
            $this->myMetaDataProvider = new TextElementMetaDataProvider();
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function getTitle() {
            return $this->_title;
        }
        
        public function setText($text) {
            $this->_text = $text;
        }
        
        public function getText() {
            return $this->_text;
        }
        
        public function getStatics() {
            return new TextElementStatics();
        }
        
        public function getBackendVisual() {
            return new TextElementEditorVisual($this);
        }

        public function getFrontendVisual($current_page) {
            return new TextElementFrontendVisual($current_page, $this);
        }
        
        public function initializeMetaData() {
            $this->myMetaDataProvider->getMetaData($this);
        }
        
        public function updateMetaData() {
            $this->myMetaDataProvider->updateMetaData($this);
        }

        public function getRequestHandler() {
            return new TextElementRequestHandler($this);
        }
        
    }
    
    class TextElementMetaDataProvider {
        
        public function getMetaData($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT title, text FROM text_elements_metadata WHERE element_id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $element->setTitle($row['title']);
                $element->setText($row['text']);
            }
        }
        
        public function updateMetaData($element) {
            // check if the metadata exists first
            $mysql_database = MysqlConnector::getInstance(); 
            
            
            if ($this->persisted($element)) {
                $query = "UPDATE text_elements_metadata SET title = '" . $mysql_database->realEscapeString($element->getTitle()) . "', text = '"
                          . $mysql_database->realEscapeString($element->getText()) . "' WHERE element_id = " . $element->getId();
            } else {
                $query = "INSERT INTO text_elements_metadata (title, text, element_id) VALUES 
                          ('" . $element->getTitle() . "', '" . $element->getText() . "', " . $element->getId() . ")"; 
            }
            $mysql_database->executeQuery($query);        
        }
        
        // checks if the metadata is already persisted
        private function persisted($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            $query = "SELECT t.id, e.id FROM text_elements_metadata t, elements e WHERE t.element_id = " . $element->getId() . "
                      AND e.id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                return true;
            }
            return false;
        }
        
    }
    
?>