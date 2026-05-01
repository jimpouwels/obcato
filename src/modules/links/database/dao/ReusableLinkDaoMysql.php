<?php

namespace Pageflow\Core\modules\links\database\dao;

use Pageflow\Core\database\MysqlConnector;
use Pageflow\Core\modules\links\model\ReusableLink;
use Pageflow\Core\modules\links\model\ReusableLinkFolder;

class ReusableLinkDaoMysql implements ReusableLinkDao {

    private static ?ReusableLinkDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ReusableLinkDaoMysql {
        if (!self::$instance) {
            self::$instance = new ReusableLinkDaoMysql();
        }
        return self::$instance;
    }

    public function getLink(int $id): ?ReusableLink {
        $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM reusable_links WHERE id = ?');
        $statement->bind_param('i', $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return ReusableLink::constructFromRecord($row);
        }
        return null;
    }

    public function getAllLinks(): array {
        $result = $this->mysqlConnector->executeQuery('SELECT * FROM reusable_links ORDER BY title');
        $links = [];
        while ($row = $result->fetch_assoc()) {
            $links[] = ReusableLink::constructFromRecord($row);
        }
        return $links;
    }

    public function getLinksByFolder(?int $folderId): array {
        if ($folderId === null) {
            $result = $this->mysqlConnector->executeQuery('SELECT * FROM reusable_links WHERE folder_id IS NULL ORDER BY title');
        } else {
            $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM reusable_links WHERE folder_id = ? ORDER BY title');
            $statement->bind_param('i', $folderId);
            $result = $this->mysqlConnector->executeStatement($statement);
        }
        $links = [];
        while ($row = $result->fetch_assoc()) {
            $links[] = ReusableLink::constructFromRecord($row);
        }
        return $links;
    }

    public function searchLinks(string $keyword): array {
        $statement = $this->mysqlConnector->prepareStatement(
            'SELECT * FROM reusable_links WHERE title LIKE ? OR url LIKE ? ORDER BY title LIMIT 30'
        );
        $like = '%' . $keyword . '%';
        $statement->bind_param('ss', $like, $like);
        $result = $this->mysqlConnector->executeStatement($statement);
        $links = [];
        while ($row = $result->fetch_assoc()) {
            $links[] = ReusableLink::constructFromRecord($row);
        }
        return $links;
    }

    public function createLink(ReusableLink $link): void {
        $statement = $this->mysqlConnector->prepareStatement(
            'INSERT INTO reusable_links (title, url, folder_id) VALUES (?, ?, ?)'
        );
        $title    = $link->getTitle();
        $url      = $link->getUrl();
        $folderId = $link->getFolderId();
        $statement->bind_param('ssi', $title, $url, $folderId);
        $this->mysqlConnector->executeStatement($statement);
        $link->setId($this->mysqlConnector->getInsertId());
    }

    public function updateLink(ReusableLink $link): void {
        $statement = $this->mysqlConnector->prepareStatement(
            'UPDATE reusable_links SET title = ?, url = ?, folder_id = ? WHERE id = ?'
        );
        $title    = $link->getTitle();
        $url      = $link->getUrl();
        $folderId = $link->getFolderId();
        $id       = $link->getId();
        $statement->bind_param('ssii', $title, $url, $folderId, $id);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function deleteLink(int $id): void {
        $statement = $this->mysqlConnector->prepareStatement('DELETE FROM reusable_links WHERE id = ?');
        $statement->bind_param('i', $id);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function moveLinkToFolder(int $linkId, ?int $folderId): void {
        $statement = $this->mysqlConnector->prepareStatement('UPDATE reusable_links SET folder_id = ? WHERE id = ?');
        $statement->bind_param('ii', $folderId, $linkId);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function getFolder(int $id): ?ReusableLinkFolder {
        $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM link_folders WHERE id = ?');
        $statement->bind_param('i', $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return ReusableLinkFolder::constructFromRecord($row);
        }
        return null;
    }

    public function getAllFolders(): array {
        $result = $this->mysqlConnector->executeQuery('SELECT * FROM link_folders ORDER BY name');
        $folders = [];
        while ($row = $result->fetch_assoc()) {
            $folders[] = ReusableLinkFolder::constructFromRecord($row);
        }
        return $folders;
    }

    public function getRootFolders(): array {
        $result = $this->mysqlConnector->executeQuery('SELECT * FROM link_folders WHERE parent_folder_id IS NULL ORDER BY name');
        $folders = [];
        while ($row = $result->fetch_assoc()) {
            $folders[] = ReusableLinkFolder::constructFromRecord($row);
        }
        return $folders;
    }

    public function getFolderTree(): array {
        $allFolders = $this->getAllFolders();
        $allLinks   = $this->getAllLinks();

        // Index folders by id
        $folderMap = [];
        foreach ($allFolders as $folder) {
            $folderMap[$folder->getId()] = $folder;
        }

        // Assign links to their folder (or root)
        $rootLinks = [];
        foreach ($allLinks as $link) {
            $fid = $link->getFolderId();
            if ($fid !== null && isset($folderMap[$fid])) {
                $folderMap[$fid]->addLink($link);
            } else {
                $rootLinks[] = $link;
            }
        }

        // Build tree: assign sub-folders to parents; collect roots
        $rootFolders = [];
        foreach ($allFolders as $folder) {
            $pid = $folder->getParentFolderId();
            if ($pid !== null && isset($folderMap[$pid])) {
                $folderMap[$pid]->addSubFolder($folder);
            } else {
                $rootFolders[] = $folder;
            }
        }

        return ['folders' => $rootFolders, 'links' => $rootLinks];
    }

    public function createFolder(ReusableLinkFolder $folder): void {
        $statement = $this->mysqlConnector->prepareStatement(
            'INSERT INTO link_folders (name, parent_folder_id) VALUES (?, ?)'
        );
        $name     = $folder->getName();
        $parentId = $folder->getParentFolderId();
        $statement->bind_param('si', $name, $parentId);
        $this->mysqlConnector->executeStatement($statement);
        $folder->setId($this->mysqlConnector->getInsertId());
    }

    public function updateFolder(ReusableLinkFolder $folder): void {
        $statement = $this->mysqlConnector->prepareStatement(
            'UPDATE link_folders SET name = ?, parent_folder_id = ? WHERE id = ?'
        );
        $name     = $folder->getName();
        $parentId = $folder->getParentFolderId();
        $id       = $folder->getId();
        $statement->bind_param('sii', $name, $parentId, $id);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function deleteFolder(int $id): void {
        $statement = $this->mysqlConnector->prepareStatement('DELETE FROM link_folders WHERE id = ?');
        $statement->bind_param('i', $id);
        $this->mysqlConnector->executeStatement($statement);
    }
}
