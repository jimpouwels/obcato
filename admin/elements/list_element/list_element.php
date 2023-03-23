<?php

    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "elements/list_element/list_item.php";
    require_once CMS_ROOT . "elements/list_element/visuals/list_element_statics.php";
    require_once CMS_ROOT . "elements/list_element/visuals/list_element_editor.php";
    require_once CMS_ROOT . "frontend/list_element_visual.php";
    require_once CMS_ROOT . "elements/list_element/list_element_request_handler.php";

    class ListElement extends Element {

        private $_title;
        private $_list_items;
        private $_metadata_provider;
            
        public function __construct() {
            $this->_metadata_provider = new ListElementMetaDataProvider();
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function getTitle() {
            return $this->_title;
        }
        
        public function getListItems() {
            return $this->_list_items;
        }
        
        public function setListItems($list_items) {
            $this->_list_items = $list_items;
        }
        
        public function addListItem() {
            $list_item = new ListItem();
            $list_item->setIndent(0);
            array_push($this->_list_items, $list_item);
        }
        
        public function deleteListItem($list_item) {
            $this->_metadata_provider->deleteListItem($this, $list_item);
        }
        
        public function getStatics() {
            return new ListElementStatics();
        }
        
        public function getBackendVisual() {
            return new ListElementEditorVisual($this);
        }

        public function getFrontendVisual($current_page) {
            return new ListElementFrontendVisual($current_page, $this);
        }
        
        public function initializeMetaData() {
            $this->_metadata_provider->getMetaData($this);
        }
        
        public function updateMetaData() {
            $this->_metadata_provider->updateMetaData($this);
        }

        public function getRequestHandler() {
            return new ListElementRequestHandler($this);
        }

        public function getSummaryText() {
            return $this->getTitle();            
        }
    }
    
    class ListElementMetaDataProvider {
        
        // Holds the list of columns that are to be collected
        private static $myAllColumns = "i.id, i.text, i.indent, i.element_id";
        
        public function getMetaData($element) {
            // fetch default meta data first
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT title FROM list_elements_metadata WHERE element_id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $element->setTitle($row['title']);
            }
            
            // now fetch the list items
            $element->setListItems($this->getListItems($element));
        }
        
        public function deleteListItem($element, $list_item) {
            // first delete it from the database
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "DELETE FROM list_element_items WHERE id = " . $list_item->getId();
            $mysql_database->executeQuery($query);
        }
        
        public function updateMetaData($element) {
            // check if the metadata exists first
            $mysql_database = MysqlConnector::getInstance(); 
            
            
            // first handle default metadata
            if ($this->metaDataPersisted($element)) {
                $query = "UPDATE list_elements_metadata SET title = '" . $element->getTitle() . "' WHERE element_id = " . $element->getId();    
            } else {
                $query = "INSERT INTO list_elements_metadata (title, element_id) VALUES 
                          ('" . $element->getTitle() . "', " . $element->getId() . ")"; 
            }
            $mysql_database->executeQuery($query);    
            
            // now handle the list items
            if (!is_null($element->getListItems())) {
                $query = "";
                foreach ($element->getListItems() as $list_item) {
                    if (!is_null($list_item->getId())) {
                        $query = "UPDATE list_element_items SET text = '" . $list_item->getText() . "', indent = " . $list_item->getIndent() . "
                                  WHERE id = " . $list_item->getId();
                    } else {
                        $query = "INSERT INTO list_element_items (text, indent, element_id) VALUES
                            ('" . $list_item->getText() . "', " . $list_item->getIndent() . ", " . $element->getId() . ")";
                    }
                    $mysql_database->executeQuery($query);
                }
            }
        }
        
        // checks if the metadata is already persisted
        private function metaDataPersisted($element) {
            $query = "SELECT t.id, e.id FROM list_elements_metadata t, elements e WHERE t.element_id = " . $element->getId() . "
                      AND e.id = " . $element->getId();
            $mysql_database = MysqlConnector::getInstance(); 
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                return true;
            }
            return false;
        }
        
        // returns all list items
        private function getListItems($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT " . self::$myAllColumns . " FROM list_element_items i, elements e WHERE i.element_id = " . $element->getId() .
                      " AND e.id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            $list_items = array();
            $list_item = NULL;
            while ($row = $result->fetch_assoc()) {
                $list_item = new ListItem();
                $list_item->setId($row['id']);
                $list_item->setText($row['text']);
                $list_item->setIndent($row['indent']);
                $list_item->setElementId($row['element_id']);
                
                array_push($list_items, $list_item);
            }
            
            return $list_items;
        }
        
    }
    
?>