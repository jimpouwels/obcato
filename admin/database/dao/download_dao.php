<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "authentication/authenticator.php";
require_once CMS_ROOT . "database/mysql_connector.php";

class DownloadDao {

    private static ?DownloadDao $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): DownloadDao {
        if (!self::$instance) {
            self::$instance = new DownloadDao();
        }
        return self::$instance;
    }

    public function getDownload(string $id): ?Download {
        $statement = $this->_mysql_connector->prepareStatement('SELECT * FROM downloads WHERE id = ?');
        $statement->bind_param('i', $id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Download::constructFromRecord($row);
        }
        return null;
    }

    public function persistDownload(Download $download): void {
        $user = Authenticator::getCurrentUser();
        $statement = $this->_mysql_connector->prepareStatement('INSERT INTO downloads
                    (title, file_name, published, created_at, created_by) VALUES (?, ?, ?, now(), ?)');
        $title = $download->getTitle();
        $filename = $download->getFileName();
        $user_id = $user->getId();
        $statement->bind_param('ssii', $title, $filename, 0, $user_id);
        $this->_mysql_connector->executeStatement($statement);
        $download->setId($this->_mysql_connector->getInsertId());
    }

    public function updateDownload(Download $download): void {
        $statement = $this->_mysql_connector->prepareStatement('UPDATE downloads SET title = ?, published = ?, file_name = ?');
        $title = $download->getTitle();
        $filename = $download->getFileName();
        $published = $download->isPublished();
        $statement->bind_param('sis', $title, $published, $filename);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getAllDownloads(): array {
        $result = $this->_mysql_connector->executeQuery('SELECT * FROM downloads');
        $downloads = array();
        while ($row = $result->fetch_assoc()) {
            $downloads[] = Download::constructFromRecord($row);
        }
        return $downloads;
    }

    public function searchDownloads(string $search_query): array {
        $statement = $this->_mysql_connector->prepareStatement('SELECT * FROM downloads WHERE title LIKE ?');
        $query_wildcard = $search_query . '%';
        $statement->bind_param('s', $query_wildcard);
        $result = $this->_mysql_connector->executeStatement($statement);
        $downloads = array();
        while ($row = $result->fetch_assoc()) {
            $downloads[] = Download::constructFromRecord($row);
        }
        return $downloads;
    }

    public function deleteDownload(int $id): void {
        $statement = $this->_mysql_connector->prepareStatement('DELETE FROM downloads WHERE id = ?');
        $statement->bind_param('i', $id);
        $this->_mysql_connector->executeStatement($statement);
    }

}
