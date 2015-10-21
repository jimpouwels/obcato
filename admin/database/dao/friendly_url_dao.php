<?php
    defined('_ACCESS') or die;

    class FriendlyUrlDao {

        private static $instance;
        private $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance)
                self::$instance = new FriendlyUrlDao();
            return self::$instance;
        }

        public function insertFriendlyUrl($url, $element_holder) {
            $query = "INSERT INTO friendly_urls (url, element_holder_id) VALUES (?, ?)";
            $statement = $this->_mysql_connector->prepareStatement($query);

            $element_holder_id = $element_holder->getId();
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

        public function getUrlFromElementHolder($element_holder) {
            $query = "SELECT url FROM friendly_urls WHERE element_holder_id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $element_holder_id = $element_holder->getId();
            $statement->bind_param("i", $element_holder_id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return $row['url'];
        }

        public function getElementHolderIdFromUrl($url) {
            $query = "SELECT element_holder_id FROM friendly_urls WHERE url = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param("s", $url);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return $row['element_holder_id'];
        }
    }
