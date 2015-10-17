<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/page_dao.php';

    class FriendlyUrlDao {

        private static $instance;
        private $_mysql_connector;
        private $_page_dao;

        private function __construct() {
            $this->_page_dao = PageDao::getInstance();
            $this->_mysql_connector = MysqlConnector::getInstance();
            $this->_page_dao = PageDao::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance)
                self::$instance = new FriendlyUrlDao();
            return self::$instance;
        }

        public function insertOrUpdateFriendlyUrl($url, $page) {
            if ($this->getPageFromUrl($url) != null)
                $this->updateFriendlyUrl($url, $page);
            else
                $this->insertFriendlyUrl($url, $page);
        }

        public function insertFriendlyUrl($url, $page) {
            $query = "INSERT INTO friendly_urls (url, element_holder_id) VALUES (?, ?)";
            $statement = $this->_mysql_connector->prepareStatement($query);

            $element_holder_id = $page->getId();
            $statement->bind_param("si", $url, $element_holder_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function updateFriendlyUrl($url, $page) {
            $query = "UPDATE friendly_urls SET url = ? WHERE element_holder_id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);

            $element_holder_id = $page->getId();
            $statement->bind_param("si", $url, $element_holder_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function getUrlFromPage($page) {
            $query = "SELECT url FROM friendly_urls WHERE element_holder_id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $page_id = $page->getId();
            $statement->bind_param("i", $page_id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return $row['url'];
        }

        public function getPageFromUrl($url) {
            $query = "SELECT element_holder_id FROM friendly_urls WHERE url = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param("s", $url);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return $this->_page_dao->getPageByElementHolderId($row['element_holder_id']);
        }

    }
