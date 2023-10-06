<?php
require_once CMS_ROOT . "/database/dao/DownloadDao.php";
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";

class DownloadDaoMysql implements DownloadDao {

    private static ?DownloadDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): DownloadDaoMysql {
        if (!self::$instance) {
            self::$instance = new DownloadDaoMysql();
        }
        return self::$instance;
    }

    public function getDownload(string $id): ?Download {
        $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM downloads WHERE id = ?');
        $statement->bind_param('i', $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Download::constructFromRecord($row);
        }
        return null;
    }

    public function persistDownload(Download $download): void {
        $user = Authenticator::getCurrentUser();
        $statement = $this->mysqlConnector->prepareStatement('INSERT INTO downloads
                    (title, file_name, published, created_at, created_by) VALUES (?, ?, ?, now(), ?)');
        $title = $download->getTitle();
        $filename = $download->getFileName();
        $userId = $user->getId();
        $statement->bind_param('ssii', $title, $filename, 0, $userId);
        $this->mysqlConnector->executeStatement($statement);
        $download->setId($this->mysqlConnector->getInsertId());
    }

    public function updateDownload(Download $download): void {
        $statement = $this->mysqlConnector->prepareStatement('UPDATE downloads SET title = ?, published = ?, file_name = ?');
        $title = $download->getTitle();
        $filename = $download->getFileName();
        $published = $download->isPublished();
        $statement->bind_param('sis', $title, $published, $filename);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function getAllDownloads(): array {
        $result = $this->mysqlConnector->executeQuery('SELECT * FROM downloads');
        $downloads = array();
        while ($row = $result->fetch_assoc()) {
            $downloads[] = Download::constructFromRecord($row);
        }
        return $downloads;
    }

    public function searchDownloads(string $searchQuery): array {
        $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM downloads WHERE title LIKE ?');
        $queryWildcard = $searchQuery . '%';
        $statement->bind_param('s', $queryWildcard);
        $result = $this->mysqlConnector->executeStatement($statement);
        $downloads = array();
        while ($row = $result->fetch_assoc()) {
            $downloads[] = Download::constructFromRecord($row);
        }
        return $downloads;
    }

    public function deleteDownload(int $id): void {
        $statement = $this->mysqlConnector->prepareStatement('DELETE FROM downloads WHERE id = ?');
        $statement->bind_param('i', $id);
        $this->mysqlConnector->executeStatement($statement);
    }

}
