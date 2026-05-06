<?php

namespace Pageflow\Core\database\dao;

use Pageflow\Core\database\MysqlConnector;
use Pageflow\Core\modules\images\model\FunctionalImage;
use Pageflow\Core\modules\images\model\FunctionalImageFolder;
use Pageflow\Core\utilities\ImageUtility;

class FunctionalImageDaoMysql implements FunctionalImageDao {

    private static ?FunctionalImageDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): FunctionalImageDaoMysql {
        if (!self::$instance) {
            self::$instance = new FunctionalImageDaoMysql();
        }
        return self::$instance;
    }

    public function getFunctionalImage(?int $id): ?FunctionalImage {
        if (!$id) return null;
        $stmt = $this->mysqlConnector->prepareStatement('SELECT * FROM functional_images WHERE id = ?');
        $stmt->bind_param('i', $id);
        $result = $this->mysqlConnector->executeStatement($stmt);
        while ($row = $result->fetch_assoc()) {
            return FunctionalImage::constructFromRecord($row);
        }
        return null;
    }

    public function getFunctionalImageFolder(?int $id): ?FunctionalImageFolder {
        if (!$id) return null;
        $stmt = $this->mysqlConnector->prepareStatement('SELECT * FROM functional_image_folders WHERE id = ?');
        $stmt->bind_param('i', $id);
        $result = $this->mysqlConnector->executeStatement($stmt);
        while ($row = $result->fetch_assoc()) {
            return FunctionalImageFolder::constructFromRecord($row);
        }
        return null;
    }

    public function getFolderTree(): array {
        $allFolders = $this->getAllFolders();
        $allImages  = $this->getAllImages();

        $folderMap = [];
        foreach ($allFolders as $folder) {
            $folderMap[$folder->getId()] = $folder;
        }

        $rootImages = [];
        foreach ($allImages as $image) {
            $fid = $image->getFolderId();
            if ($fid !== null && isset($folderMap[$fid])) {
                $folderMap[$fid]->addImage($image);
            } else {
                $rootImages[] = $image;
            }
        }

        $rootFolders = [];
        foreach ($allFolders as $folder) {
            $pid = $folder->getParentFolderId();
            if ($pid !== null && isset($folderMap[$pid])) {
                $folderMap[$pid]->addSubFolder($folder);
            } else {
                $rootFolders[] = $folder;
            }
        }

        return ['folders' => $rootFolders, 'images' => $rootImages];
    }

    private function getAllFolders(): array {
        $result = $this->mysqlConnector->executeQuery('SELECT * FROM functional_image_folders ORDER BY name');
        $folders = [];
        while ($row = $result->fetch_assoc()) {
            $folders[] = FunctionalImageFolder::constructFromRecord($row);
        }
        return $folders;
    }

    private function getAllImages(): array {
        $result = $this->mysqlConnector->executeQuery('SELECT * FROM functional_images ORDER BY title');
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = FunctionalImage::constructFromRecord($row);
        }
        return $images;
    }

    public function getAllFunctionalImages(): array {
        return $this->getAllImages();
    }

    public function createFunctionalImage(FunctionalImage $image): void {
        $stmt = $this->mysqlConnector->prepareStatement(
            'INSERT INTO functional_images (title, alt_text, file_name, folder_id, published) VALUES (?, ?, ?, ?, ?)'
        );
        $title    = $image->getTitle();
        $altText  = $image->getAltText();
        $filename = $image->getFilename();
        $folderId = $image->getFolderId();
        $published = $image->isPublished() ? 1 : 0;
        $stmt->bind_param('sssii', $title, $altText, $filename, $folderId, $published);
        $this->mysqlConnector->executeStatement($stmt);
        $image->setId($this->mysqlConnector->getInsertId());
    }

    public function updateFunctionalImage(FunctionalImage $image): void {
        $stmt = $this->mysqlConnector->prepareStatement(
            'UPDATE functional_images SET title = ?, alt_text = ?, file_name = ?, folder_id = ?, published = ? WHERE id = ?'
        );
        $title    = $image->getTitle();
        $altText  = $image->getAltText();
        $filename = $image->getFilename();
        $folderId = $image->getFolderId();
        $published = $image->isPublished() ? 1 : 0;
        $id       = $image->getId();
        $stmt->bind_param('sssiii', $title, $altText, $filename, $folderId, $published, $id);
        $this->mysqlConnector->executeStatement($stmt);
    }

    public function deleteFunctionalImage(FunctionalImage $image): void {
        ImageUtility::delete($image->getFilename());
        $stmt = $this->mysqlConnector->prepareStatement('DELETE FROM functional_images WHERE id = ?');
        $stmt->bind_param('i', $image->getId());
        $this->mysqlConnector->executeStatement($stmt);
    }

    public function createFolder(FunctionalImageFolder $folder): void {
        $stmt = $this->mysqlConnector->prepareStatement(
            'INSERT INTO functional_image_folders (name, parent_folder_id) VALUES (?, ?)'
        );
        $name     = $folder->getName();
        $parentId = $folder->getParentFolderId();
        $stmt->bind_param('si', $name, $parentId);
        $this->mysqlConnector->executeStatement($stmt);
        $folder->setId($this->mysqlConnector->getInsertId());
    }

    public function updateFolder(FunctionalImageFolder $folder): void {
        $stmt = $this->mysqlConnector->prepareStatement(
            'UPDATE functional_image_folders SET name = ?, parent_folder_id = ? WHERE id = ?'
        );
        $name     = $folder->getName();
        $parentId = $folder->getParentFolderId();
        $id       = $folder->getId();
        $stmt->bind_param('sii', $name, $parentId, $id);
        $this->mysqlConnector->executeStatement($stmt);
    }

    public function deleteFolder(int $id): void {
        $stmt = $this->mysqlConnector->prepareStatement('DELETE FROM functional_image_folders WHERE id = ?');
        $stmt->bind_param('i', $id);
        $this->mysqlConnector->executeStatement($stmt);
    }

    public function moveImageToFolder(FunctionalImage $image, ?int $folderId): void {
        $stmt = $this->mysqlConnector->prepareStatement('UPDATE functional_images SET folder_id = ? WHERE id = ?');
        $id = $image->getId();
        $stmt->bind_param('ii', $folderId, $id);
        $this->mysqlConnector->executeStatement($stmt);
        $image->setFolderId($folderId);
    }
}
