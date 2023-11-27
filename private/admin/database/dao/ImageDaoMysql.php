<?php
require_once CMS_ROOT . "/database/dao/ImageDao.php";
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/images/model/ImageLabel.php";
require_once CMS_ROOT . "/modules/images/model/Image.php";
require_once CMS_ROOT . "/database/util/QueryUtils.php";

class ImageDaoMysql implements ImageDao {

    private static string $myAllColumns = "i.id, i.title, i.alt_text, i.published, i.created_at, i.created_by, i.file_name, i.thumb_file_name";
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
        $query = "UPDATE images SET title = ?, alt_text = ?, published = ?, file_name = ?, thumb_file_name = ? WHERE id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('ssissi', $title, $altText, $published, $filename, $thumbFilename, $id);
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

    public function getAllImagesWithoutLabel(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM images i LEFT JOIN images_labels ils ON i.id = ils.image_id 
                      WHERE ils.image_id IS NULL";
        $result = $this->mysqlConnector->executeQuery($query);
        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = Image::constructFromRecord($row);
        }
        return $images;
    }

    public function searchImagesByLabels(array $labels): array {
        $allImages = array();
        foreach ($labels as $label) {
            array_push($allImages, ...$this->searchImages(null, null, $label->getId()));
        }
        return $allImages;
    }

    public function searchImages(?string $keyword, ?string $filename, ?int $labelId): array {
        $statement = new SelectStatement(true);
        $statement->addFrom("images", explode(', ', str_replace("i.", "", self::$myAllColumns)));

        if ($labelId) {
            $statement->addFrom("images_labels", ["label_id"]);
            $statement->addWhereByValue("images_labels", "label_id", WhereType::Equals, $labelId);
            $statement->addWhereByRef("images_labels", "image_id", "images", "id");
        }
        if ($keyword) {
            $statement->addWhereByValue("images", "title", WhereType::Like, $keyword);
        }
        if ($filename) {
            $statement->addWhereByValue("images", "file_name", WhereType::Like, $filename);
        }
        $statement->orderBy("images", "created_at");
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

        // delete the uploaded images
        if (!is_null($image->getFileName()) && $image->getFileName() != '') {
            $filePath = UPLOAD_DIR . "/" . $image->getFileName();
            $thumbFilePath = UPLOAD_DIR . "/" . $image->getThumbFileName();
            if (file_exists($filePath))
                unlink($filePath);
            if (file_exists($thumbFilePath))
                unlink($thumbFilePath);
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

    public function createLabel(): ImageLabel {
        $newLabel = new ImageLabel();
        $newLabel->setName("Nieuw label");
        $postfix = 1;
        while (!is_null($this->getLabelByName($newLabel->getName()))) {
            $newLabel->setName("Nieuw label " . $postfix);
            $postfix++;
        }
        $newId = $this->persistLabel($newLabel);
        $newLabel->setId($newId);

        return $newLabel;
    }

    public function getAllLabels(): array {
        $query = "SELECT * FROM image_labels";
        $result = $this->mysqlConnector->executeQuery($query);
        $labels = array();
        while ($row = $result->fetch_assoc()) {
            $labels[] = ImageLabel::constructFromRecord($row);
        }
        return $labels;
    }

    public function getLabel(int $id): ?ImageLabel {
        $query = "SELECT * FROM image_labels WHERE id = " . $id;
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ImageLabel::constructFromRecord($row);
        }
        return null;
    }

    public function getLabelByName(string $name): ?ImageLabel {
        $query = "SELECT * FROM image_labels WHERE name = '" . $name . "'";
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ImageLabel::constructFromRecord($row);
        }
        return null;
    }

    public function persistLabel(ImageLabel $label): string {
        $query = "INSERT INTO image_labels (name) VALUES  ('" . $label->getName() . "')";
        $this->mysqlConnector->executeQuery($query);
        return $this->mysqlConnector->getInsertId();
    }

    public function updateLabel(ImageLabel $label): void {
        $query = "UPDATE image_labels SET name = '" . $label->getName() .
            "' WHERE id = " . $label->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function deleteLabel(ImageLabel $label): void {
        $query = "DELETE FROM image_labels WHERE id = " . $label->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function addLabelToImage(int $labelId, Image $image): void {
        $query = "INSERT INTO images_labels (image_id, label_id) VALUES (" . $image->getId() . ", " . $labelId . ")";
        $this->mysqlConnector->executeQuery($query);
    }

    public function deleteLabelForImage(int $labelId, Image $image): void {
        $query = "DELETE FROM images_labels WHERE image_id = " . $image->getId() . "
                      AND label_id = " . $labelId;
        $this->mysqlConnector->executeQuery($query);
    }

    public function getLabelsForImage(int $labelId): array {
        $query = "SELECT il.id, il.name FROM image_labels il, images_labels ils, 
                      images i WHERE ils.image_id = " . $labelId . " AND ils.image_id =
                      i.id AND il.id = ils.label_id";
        $result = $this->mysqlConnector->executeQuery($query);
        $labels = array();
        while ($row = $result->fetch_assoc()) {
            $labels[] = ImageLabel::constructFromRecord($row);
        }

        return $labels;
    }
}