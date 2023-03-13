<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element_holder.php";
    require_once CMS_ROOT . "database/dao/block_dao.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";

    class Page extends ElementHolder {

        const ElementHolderType = "ELEMENT_HOLDER_PAGE";

        private static $TABLE_NAME = "pages";
        
        private $_page_dao;
        private $_description;
        private $_navigation_title;
        private $_parent_id;
        private $_show_in_navigation;
        private $_followup;
        private $_is_homepage;
        
        public function __construct() {
            parent::__construct();
            $this->setScopeId(5);
            $this->_page_dao = PageDao::getInstance();
        }
        
        public function getDescription() {
            return $this->_description;
        }
        
        public function setDescription($description) {
            $this->_description = $description;
        }
        
        public function getNavigationTitle() {
            return $this->_navigation_title;
        }
        
        public function setNavigationTitle($navigation_title) {
            $this->_navigation_title = $navigation_title;
        }
        
        public function getParent() {
            $parent = null;
            if (!is_null($this->_parent_id)) {
                $parent = $this->_page_dao->getPage($this->_parent_id);
            }
            return $parent;
        }
        
        public function setParentId($parent_id) {
            $this->_parent_id = $parent_id;    
        }
        
        public function getParentId() {
            return $this->_parent_id;
        }
        
        public function setParent($parent) {
            if (!is_null($parent)) {
                $this->_parent_id = $parent->getId();
            }
        }
        
        public function getShowInNavigation() {
            return $this->_show_in_navigation;
        }
        
        public function setShowInNavigation($show_in_navigation) {
            $this->_show_in_navigation = $show_in_navigation;
        }
        
        public function setFollowUp($follow_up) {
            $this->_followup = $follow_up;
        }
        
        public function getFollowup() {
            return $this->_followup;
        }
        
        public function setIsHomepage($is_homepage) {
            $this->_is_homepage = $is_homepage;
        }
        
        public function isHomepage() {
            return $this->_is_homepage;
        }
        
        public function isLast() {
            $mysql_database = MysqlConnector::getInstance();
                        
            $query = "SELECT element_holder_id FROM " . self::$TABLE_NAME . " WHERE follow_up = (SELECT MAX(follow_up)"
                     . " FROM " . self::$TABLE_NAME . " WHERE parent_id = " . $this->getParent()->getId() . ") AND"
                     . " parent_id = " . $this->getParent()->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $id = $row['element_holder_id'];
                break;
            }
            $last = false;
            if ($this->getId() == $id) {
                $last = true;
            }
            return $last;
        }
        
        public function isFirst() {
            $mysql_database = MysqlConnector::getInstance();
                        
            $query = "SELECT element_holder_id FROM " . self::$TABLE_NAME . " WHERE follow_up = (SELECT MIN(follow_up)"
                     . " FROM " . self::$TABLE_NAME . " WHERE parent_id = " . $this->getParent()->getId() . ") AND"
                     . " parent_id = " . $this->getParent()->getId();
                     
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $id = $row['element_holder_id'];
                break;
            }
            $first = false;
            if ($this->getId() == $id) {
                $first = true;
            }
            return $first;
        }
        
        public function getParents() {
            $parents = array();
            array_unshift($parents, $this);
            $parent = $this->getParent();
            if (!is_null($parent)) {
                $parents = array_merge($parent->getParents(), $parents);
            }
            return $parents;
        }
        
        public function getSubPages() {
            return $this->_page_dao->getSubPages($this);
        }
        
        public function getBlocks() {
            $block_dao = BlockDao::getInstance();
            return $block_dao->getBlocksByPage($this);
        }
        
        public function getBlocksByPosition($position) {
            $block_dao = BlockDao::getInstance();
            return $block_dao->getBlocksByPageAndPosition($this, $position);
        }
        
        public function addBlock($block) {
            $block_dao = BlockDao::getInstance();
            $block_dao->addBlockToPage($block->getId(), $this);
        }
        
        public function deleteBlock($block) {
            $block_dao = BlockDao::getInstance();
            $block_dao->deleteBlockFromPage($block->getId(), $this);
        }
        
        public function moveUp() {
            $this->_page_dao->moveUp($this);
        }
        
        public function moveDown() {
            $this->_page_dao->moveDown($this);
        }
        
        public static function constructFromRecord($record) {
            $page = new Page();
            $page->setId($record['id']);
            $page->setParentId($record['parent_id']);
            $page->setPublished($record['published'] == 1 ? true : false);
            $page->setTitle($record['title']);
            $page->setDescription($record['description']);
            $page->setNavigationTitle($record['navigation_title']);
            $page->setShowInNavigation($record['show_in_navigation'] == 1 ? true : false);
            $page->setTemplateId($record['template_id']);
            $page->setIncludeInSearchEngine($record['include_in_searchindex'] == 1 ? true : false);
            $page->setScopeId($record['scope_id']);
            $page->setCreatedAt($record['created_at']);
            $page->setCreatedById($record['created_by']);
            $page->setType($record['type']);
            $page->setFollowUp($record['follow_up']);
            $page->setIsHomepage($record['is_homepage'] == 1 ? true : false);
            return $page;
        }
    
    }
    
?>