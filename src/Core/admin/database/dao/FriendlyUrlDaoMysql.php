<?php

namespace Obcato\Core\admin\database\dao;

use Obcato\Core\admin\core\model\ElementHolder;

class FriendlyUrlDaoMysql implements FriendlyUrlDao {

    private static ?FriendlyUrlDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): FriendlyUrlDaoMysql {
        if (!self::$instance) {
            self::$instance = new FriendlyUrlDaoMysql();
        }
        return self::$instance;
    }

    public function insertFriendlyUrl(string $url, ElementHolder $elementHolder): void {
        $query = "INSERT INTO friendly_urls (url, element_holder_id) VALUES (?, ?)";
        $statement = $this->_mysql_connector->prepareStatement($query);

        $element_holder_id = $elementHolder->getId();
        $statement->bind_param("si", $url, $element_holder_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function updateFriendlyUrl(string $url, ElementHolder $elementHolder): void {
        $query = "UPDATE friendly_urls SET url = ? WHERE element_holder_id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);

        $element_holder_id = $elementHolder->getId();
        $statement->bind_param("si", $url, $element_holder_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getUrlFromElementHolder(ElementHolder $elementHolder): ?string {
        $query = "SELECT url FROM friendly_urls WHERE element_holder_id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $element_holder_id = $elementHolder->getId();
        $statement->bind_param("i", $element_holder_id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return $row['url'];
        }
        return null;
    }

    public function getElementHolderIdFromUrl(string $url): ?int {
        $query = "SELECT element_holder_id FROM friendly_urls WHERE url = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param("s", $url);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return $row['element_holder_id'];
        }
        return null;
    }
}
