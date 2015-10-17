<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/presentable.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/element_holder_dao.php";

    abstract class Element extends Presentable {
    
        private $_index;
        private $_element_holder_id;
                
        public function setIndex($index) {
            $this->_index = $index;
        }
        
        public function getIndex() {
            return $this->_index;
        }
        
        public function getType() {
            $element_dao = ElementDao::getInstance();
            return $element_dao->getElementTypeForElement($this->getId());
        }
        
        public function getElementHolderId() {
            return $this->_element_holder_id;
        }
        
        public function setElementHolderId($element_holder_id) {
            $this->_element_holder_id = $element_holder_id;
        }
        
        public function getElementHolder() {
            $element_holder_dao = ElementHolderDao::getInstance();
            return $element_holder_dao->getElementHolder($this->_element_holder_id);
        }
        
        public function delete() {
            $element_dao = ElementDao::getInstance();
            $element_dao->deleteElement($this);
        }
        
        public static function constructFromRecord($record) {
            include_once CMS_ROOT . 'elements/' . $record['identifier'] . '/' . $record['domain_object'];
            
            // first get the element type
            $element_type = $record['classname'];
            
            // the constructor for each type will initialize specific metadata
            $element = new $element_type;
            
            $element->setId($record['id']);
            $element->setIndex($record['follow_up']);
            $element->setTemplateId($record['template_id']);
            $element->setElementHolderId($record['element_holder_id']);
            
            // initialize element specific metadata
            $element->initializeMetaData();
            
            return $element;
        }
        
        public abstract function getStatics();
        
        public abstract function getBackendVisual();

        public abstract function getFrontendVisual($current_page);

        public abstract function getRequestHandler();
        
        public abstract function initializeMetaData();
        
        public abstract function updateMetaData();
    
    }
    
?>