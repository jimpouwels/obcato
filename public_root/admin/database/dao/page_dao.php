<?php

    
    defined('_ACCESS') or die;
    
    include_once CMS_ROOT . "database/mysql_connector.php";
    include_once CMS_ROOT . "database/dao/element_dao.php";
    include_once CMS_ROOT . "database/dao/element_holder_dao.php";
    include_once CMS_ROOT . "database/dao/block_dao.php";
    include_once CMS_ROOT . "database/dao/template_dao.php";
    include_once CMS_ROOT . "core/model/page.php";
    include_once CMS_ROOT . "database/dao/authorization_dao.php";

    class PageDao {

        private static $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id, 
                      e.created_at, e.created_by, e.type, p.navigation_title, p.description, p.parent_id, p.show_in_navigation,
                      p.include_in_searchindex, p.follow_up, p.is_homepage";
    

        private static $instance;
        private $_mysql_connector;
        private $_element_holder_dao;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
            $this->_element_holder_dao = ElementHolderDao::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance)
                self::$instance = new PageDao();
            return self::$instance;
        }

        public function getPage($id) {
            $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                    element_holders e WHERE e.id = ? AND e.id = p.element_holder_id");
            $statement->bind_param("i", $id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return Page::constructFromRecord($row);
        }

        public function getRootPages() {
            $query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE p.parent_id IS NULL
                      AND e.id = p.element_holder_id";
            $result = $this->_mysql_connector->executeQuery($query);
            $pages = array();
            while ($row = $result->fetch_assoc())
                $pages[] = Page::constructFromRecord($row);
            return $pages;
        }

        public function getSubPages($page) {
            $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                    element_holders e WHERE p.parent_id = ?
                                                                    AND p.element_holder_id = e.id ORDER BY p.follow_up");
            $id = $page->getId();
            $statement->bind_param("i", $id);
            $result = $this->_mysql_connector->executeStatement($statement);
            $pages = array();
            while ($row = $result->fetch_assoc())
                $pages[] = Page::constructFromRecord($row);
            return $pages;
        }

        public function persist($page) {
            $this->_element_holder_dao->persist($page);
            $query = "INSERT INTO pages (navigation_title, parent_id, show_in_navigation, include_in_searchindex, element_holder_id,
                     follow_up, is_homepage, description) VALUES ('" . $page->getNavigationTitle() . "', " . $page->getParentId() . "," 
                     . $page->getShowInNavigation() . ", 1, " . $page->getId() . ", 1, 0, '')";
            $this->_mysql_connector->executeQuery($query);
        }

        public function updatePage($page) {
            $query = "UPDATE pages SET navigation_title = '" . $this->_mysql_connector->realEscapeString($page->getNavigationTitle()) . "', show_in_navigation = " . $page->getShowInNavigation() . ",
                    include_in_searchindex = " . $page->getIncludeInSearchEngine() . ", follow_up = " . $page->getFollowUp() . ", description = '" . 
                      $page->getDescription() . "'";
            $query .= " WHERE element_holder_id = " . $page->getId();
            $this->_mysql_connector->executeQuery($query);
            $this->_element_holder_dao->update($page);
        }

        public function deletePage($page) {
            $this->_element_holder_dao->delete($page);
        }

        public function searchByTerm($term) {
            $query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE e.id = p.element_holder_id 
                      AND title like '" . $term . "%'";
            $result = $this->_mysql_connector->executeQuery($query);
            $pages = array();
            while ($row = $result->fetch_assoc())
                $pages[] = Page::constructFromRecord($row);
            return $pages;
        }

        public function moveUp($page) {
            $query1 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE follow_up = " . ($page->getFollowUp() - 1);
            $query2 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE element_holder_id = " . $page->getId();
            
            $this->_mysql_connector->executeQuery($query1);
            $this->_mysql_connector->executeQuery($query2);
        }

        public function moveDown($page) {
            $query1 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE follow_up = " . ($page->getFollowUp() + 1);
            $query2 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE element_holder_id = " . $page->getId();
            $this->_mysql_connector->executeQuery($query1);
            $this->_mysql_connector->executeQuery($query2);
        }
    }
?>