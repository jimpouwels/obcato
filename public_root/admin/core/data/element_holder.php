<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/data/presentable.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/link_dao.php";
    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class ElementHolder extends Presentable {
        
        private $_element_holder_dao;
        private $_title;
        private $_published;
        private $_include_in_search_engine;
        private $_created_at;
        private $_created_by_id;
        
        public function __construct() {
            $this->_element_holder_dao = ElementHolderDao::getInstance();
        }
        
        public function isPublished() {
            return $this->_published;
        }
        
        public function getTitle() {
            return $this->_title;
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function setPublished($published) {
            $this->_published = $published;
        }
        
        public function getIncludeInSearchEngine() {
            return $this->_include_in_search_engine;
        }
        
        public function setIncludeInSearchEngine($include_in_search_engine) {
            $this->_include_in_search_engine = $include_in_search_engine;
        }
        
        public function getElements() {
            $dao = ElementDao::getInstance();
            return $dao->getElements($this);
        }
        
        public function getCreatedAt() {
            return $this->_created_at;
        }
        
        public function setCreatedAt($created_at) {
            $this->_created_at = $created_at;
        }
        
        public function getCreatedBy() {
            $authorization_dao = AuthorizationDao::getInstance();
            return $authorization_dao->getUserById($this->_created_by_id);
        }
        
        public function setCreatedById($created_by_id) {
            $this->_created_by_id = $created_by_id;
        }
        
        public function getType() {
            return $this->myType;
        }
        
        public function setType($type) {
            $this->myType = $type;
        }
        
        public function getLinks() {
            $link_dao = LinkDao::getInstance();
            return $link_dao->getLinksForElementHolder($this->getId());
        }
        
        public function getElementStatics() {
            $element_statics = array();
            foreach ($this->getElements() as $element) {
                $key = $element->getType()->getName();
                if (!array_key_exists($key, $element_statics)) {
                    $statics = $element->getStatics();
                    if (!is_null($statics)) {
                        $element_statics[$key] = $element->getStatics();
                    }
                }
            }
            return $element_statics;
        }
        
        public function update() {
            $this->_element_holder_dao->update($this);
        }
        
        public function delete() {
            $this->_element_holder_dao->delete($this);
        }
        
        public static function constructFromRecord($record) {
            $element_holder = new ElementHolder();
            $element_holder->setId($record['id']);
            $element_holder->setPublished($record['published'] == 1 ? true : false);
            $element_holder->setTitle($record['title']);
            $element_holder->setTemplateId($record['template_id']);
            $element_holder->setScopeId($record['scope_id']);
            $element_holder->setCreatedAt($record['created_at']);
            $element_holder->setCreatedById($record['created_by']);
            $element_holder->setType($record['type']);
            
            return $element_holder;
        }
    
    }
    
?>