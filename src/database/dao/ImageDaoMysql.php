<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\database\SelectStatement;
use Obcato\Core\database\WhereType;
use Obcato\Core\modules\images\model\Image;
use const Obcato\Core\UPLOAD_DIR;

class ImageDaoMysql implements ImageDao {

    private static string $myAllColumns = "i.id, i.title, i.alt_text, i.published, i.created_at, i.created_by, i.file_name, i.thumb_file_name, i.location";
    private static ?ImageDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ImageDaoMysql {
        if (!self::$instance) {
            self::$instance = new ImageDaoMysql();
        }
        return self::$instance;
    }

    public function getImage(?int $imageId): ?Image {
        if (!$imageId) {
            return null;
        }
        $query = "SELECT " . self::$myAllColumns . " FROM images i WHERE id = " . $imageId;
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Image::constructFromRecord($row);
        }
        return null;
    }

    public function updateImage(Image $image): void {
        $title = $image->getTitle();
        $altText = $image->getAltText();
        $published = $image->isPublished() ? 1 : 0;
        $filename = $image->getFilename();
        $id = $image->getId();
        $thumbFilename = $image->getThumbFileName();
        $location = $image->getLocation();
        $query = "UPDATE images SET title = ?, alt_text = ?, published = ?, file_name = ?, thumb_file_name = ?, location = ? WHERE id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('ssisssi', $title, $altText, $published, $filename, $thumbFilename, $location, $id);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function getAllImages(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM images i";
        $result = $this->mysqlConnector->executeQuery($query);
        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = Image::constructFromRecord($row);
        }
        return $images;
    }

    public function searchImages(?string $keyword, ?string $filename, ?int $limit = 500): array {
        $statement = new SelectStatement(true);
        $statement->from("images", explode(', ', str_replace("i.", "", self::$myAllColumns)));

        if ($keyword) {
            $statement->where("images", "title", WhereType::Like, $keyword);
        }
        if ($filename) {
            $statement->where("images", "file_name", WhereType::Like, $filename);
        }
        $statement->orderBy("images", "created_at");
        $statement->limit($limit);
        $result = $statement->execute($this->mysqlConnector);
        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = Image::constructFromRecord($row);
        }
        return $images;
    }

    public function createImage(): Image {
        $newImage = new Image();
        $newImage->setPublished(false);
        $newImage->setTitle('Nieuwe afbeelding');
        $newImage->setCreatedById(Authenticator::getCurrentUser()->getId());
        $this->persistImage($newImage);
        return $newImage;
    }

    public function deleteImage($image): void {
        $query = "DELETE FROM images WHERE id = " . $image->getId();

        if (!is_null($image->getFileName()) && $image->getFileName() != '') {
            $filePath = UPLOAD_DIR . "/" . $image->getFileName();
            $thumbFilePath = UPLOAD_DIR . "/" . $image->getThumbFileName();
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            if (file_exists($thumbFilePath)) {
                unlink($thumbFilePath);
            }
        }
        $this->mysqlConnector->executeQuery($query);
    }

    private function persistImage($image): void {
        $query = "INSERT INTO images (title, published, created_at, created_by, file_name, thumb_file_name)
                      VALUES ('" . $image->getTitle() . "', " . ($image->isPublished() ? 1 : 0) . ", now(), " .
            $image->getCreatedById() . ", NULL, NULL)";
        $this->mysqlConnector->executeQuery($query);
        $image->setId($this->mysqlConnector->getInsertId());
    }
}
