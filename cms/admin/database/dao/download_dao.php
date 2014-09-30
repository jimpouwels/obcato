<?php
    // No direct access
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "/database/mysql_connector.php";
    require_once CMS_ROOT . "/database/dao/authorization_dao.php";

    class DownloadDao {

        private static $instance;
        private $_mysql_connector;
        private $_authorization_dao;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
            $this->_authorization_dao = AuthorizationDao::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new DownloadDao();
            }
            return self::$instance;
        }

        public function persistDownload($download) {
            $user = $this->_authorization_dao->getUser($_SESSION["username"]);
            $statement = $this->_mysql_connector->prepareStatement("INSERT INTO downloads
                    (title, file_name, published, created_at, created_by) VALUES (?, ?, ?, now(), ?)");
            $statement->bind_param("ssii", $download->getTitle(), $download->getFileName(), $published = 0, $user->getId());
            $this->_mysql_connector->executeStatement($statement);
            $download->setId($this->_mysql_connector->getInsertId());
        }

        public function getAllDownloads() {
            $result = $this->_mysql_connector->executeQuery("SELECT * FROM downloads");
            $downloads = array();
            while ($row = $result->fetch_assoc())
                $downloads[] = Download::constructFromRecord($row);
            return $downloads;
        }

    }