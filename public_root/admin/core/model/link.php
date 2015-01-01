<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/element_holder_dao.php";

    class Link extends Entity {
        
        const INTERNAL = "INTERNAL";
        const EXTERNAL = "EXTERNAL";
    
        private $_title;
        private $_url;
        private $_type;
        private $_code;
        private $_targetElementHolderId;
        private $_parentElementHolderId;
        private $_target;
        private $_element_holder_dao;

        public function __construct() {
            $this->_element_holder_dao = ElementHolderDao::getInstance();
        }

        public function getTitle() {
            return $this->_title;
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function getTargetAddress() {
            return $this->_url;
        }
        
        public function setTargetAddress($url) {
            $this->_url = $url;
        }
        
        public function getType() {
            return $this->_type;
        }
        
        public function setType($type) {
            $this->_type = $type;
        }
        
        public function getTargetElementHolder() {
            $element_holder = NULL;
            if (!is_null($this->_targetElementHolderId) && $this->_targetElementHolderId != '') {
                $element_holder = $this->getElementHolder($this->_targetElementHolderId);
            }
            return $element_holder;
        }
        
        public function getParentElementHolder() {
            $element_holder = NULL;
            if (!is_null($this->_parentElementHolderId) && $this->_parentElementHolderId != '') {
                $element_holder = $this->getElementHolder($this->_parentElementHolderId);
            }
            return $element_holder;
        }
        
        public function getTargetElementHolderId() {
            return $this->_targetElementHolderId;
        }
        
        public function setTargetElementHolderId($target_element_holder_id) {
            $this->_targetElementHolderId = $target_element_holder_id;
        }
        
        public function getParentElementHolderId() {
            return $this->_parentElementHolderId;
        }
        
        public function setParentElementHolderId($parent_element_holder_id) {
            $this->_parentElementHolderId = $parent_element_holder_id;
        }
        
        public function getCode() {
            return $this->_code;
        }
        
        public function setCode($code) {
            $this->_code = $code;
        }

        public function getTarget() {
            return $this->_target;
        }

        public function setTarget($target) {
            $this->_target = $target;
        }
        
        private function getElementHolder($element_holder_id) {
            return $this->_element_holder_dao->getElementHolder($element_holder_id);
        }
        
        public static function constructFromRecord($record) {
            $link = new Link();
            $link->setId($record['id']);
            $link->setTitle($record['title']);
            $link->setTargetAddress($record['target_address']);
            $link->setType($record['type']);
            $link->setCode($record['code']);
            $link->setTarget($record['target']);
            $link->setParentElementHolderId($record['parent_element_holder']);
            $link->setTargetElementHolderId($record['target_element_holder']);
            
            return $link;
        }
    }
    
?>