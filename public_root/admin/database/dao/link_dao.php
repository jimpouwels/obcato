<?php
    defined('_ACCESS') or die;

    include_once CMS_ROOT . "database/mysql_connector.php";
    include_once CMS_ROOT . "core/data/link.php";

    class LinkDao {

        private static $instance;
        private $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance)
                self::$instance = new LinkDao();
            return self::$instance;
        }

        public function createLink($element_holder_id) {
            $new_link = new Link();
            $new_link->setTitle('Nieuwe link');
            $new_link->setCode($new_link->getId());
            $new_link->setParentElementHolderId($element_holder_id);
            $new_link->setType(Link::INTERNAL);
            $this->persistLink($new_link);
            return $new_link;
        }

        public function persistLink($new_link) {
            $query = "INSERT INTO links (title, target_address, type, code, target, target_element_holder, parent_element_holder)
                      VALUES ('" . $new_link->getTitle() . "', NULL, '" . $new_link->getType() . 
                      "', '" . $new_link->getCode() . "', '_self', NULL, " . $new_link->getParentElementHolderId() . ")";
            $this->_mysql_connector->executeQuery($query);
            $new_link->setId($this->_mysql_connector->getInsertId());
        }

        public function getLinksForElementHolder($element_holder_id) {
            $query = "SELECT * FROM links WHERE parent_element_holder = " . $element_holder_id;
            $result = $this->_mysql_connector->executeQuery($query);
            $links = array();
            while ($row = $result->fetch_assoc())
                $links[] = Link::constructFromRecord($row);
            return $links;
        }

        public function deleteLink($link) {
            $statement = $this->_mysql_connector->prepareStatement('DELETE FROM links WHERE id = ?');
            $link_id = $link->getId();
            $statement->bind_param('i', $link_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function updateLink($link) {
            if (!is_null($link->getTargetElementHolder()) && $link->getTargetElementHolder() != '')
                $link->setType(Link::INTERNAL);
            else
                $link->setType(Link::EXTERNAL);
            
            $query = "UPDATE links SET title = '" . $link->getTitle() . "', target_address = '" . $link->getTargetAddress() . "',
                      code = '" . $link->getCode() . "', target = '" . $link->getTarget() . "', type = '" . $link->getType() . "'";
            
            if ($link->getTargetElementHolderId() != '' && !is_null($link->getTargetElementHolderId()))
                $query = $query . ", target_element_holder = " . $link->getTargetElementHolderId();
            else
                $query = $query . ", target_element_holder = NULL";
            $query = $query . " WHERE id = " . $link->getId();
            $this->_mysql_connector->executeQuery($query);
        }

        public function getBrokenLinks() {
            $query = "SELECT * FROM links WHERE target_address IS NULL AND target_element_holder IS NULL";
            $result = $this->_mysql_connector->executeQuery($query);
            $links = array();
            while ($row = $result->fetch_assoc())
                $links[] = Link::constructFromRecord($row);
            return $links;            
        }
        
    }
?>