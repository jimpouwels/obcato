<?php
require_once CMS_ROOT . "/database/dao/ImageDao.php";
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/images/model/ImageLabel.php";
require_once CMS_ROOT . "/modules/images/model/Image.php";

class ImageDaoMysql implements ImageDao {

    private static string $myAllColumns = "i.id, i.title, i.alt_text, i.published, i.created_at, i.created_by, i.file_name, i.thumb_file_name";
    private static ?ImageDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
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
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Image::constructFromRecord($row);
        }
        return null;
    }

    public function updateImage(Image $image): void {
        $title = $image->getTitle();
        $alt_text = $image->getAltText();
        $published = $image->isPublished() ? 1 : 0;
        $filename = $image->getFileName();
        $id = $image->getId();
        $thumb_filename = $image->getThumbFileName();
        $query = "UPDATE images SET title = ?, alt_text = ?, published = ?, file_name = ?, thumb_file_name = ? WHERE id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('ssissi', $title, $alt_text, $published, $filename, $thumb_filename, $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getAllImages(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM images i";
        $result = $this->_mysql_connector->executeQuery($query);
        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = Image::constructFromRecord($row);
        }
        return $images;
    }

    public function getAllImagesWithoutLabel(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM images i LEFT JOIN images_labels ils ON i.id = ils.image_id 
                      WHERE ils.image_id IS NULL";
        $result = $this->_mysql_connector->executeQuery($query);
        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = Image::constructFromRecord($row);
        }
        return $images;
    }

    public function searchImagesByLabels(array $labels): array {
        $all_images = array();
        foreach ($labels as $label) {
            array_push($all_images, ...$this->searchImages(null, null, $label->getId()));
        }
        return $all_images;
    }

    public function searchImages(?string $keyword, ?string $filename, ?int $label_id): array {
        $query = "SELECT DISTINCT " . self::$myAllColumns . " FROM images i";

        if (!is_null($label_id)) {
            $query = $query . ", images_labels ils WHERE ils.label_id = " . $label_id . " AND ils.image_id = i.id";
        }
        if (!is_null($keyword)) {
            $pos = strpos($query, 'WHERE');
            if ($pos) {
                $query = $query . ' AND';
            } else {
                $query = $query . ' WHERE';
            }
            $query = $query . " i.title LIKE '" . $keyword . "%'";
        }
        if (!is_null($filename)) {
            $pos = strpos($query, 'WHERE');
            if ($pos) {
                $query = $query . ' AND';
            } else {
                $query = $query . ' WHERE';
            }
            $query = $query . " i.file_name LIKE '" . $filename . "%'";
        }
        $query = $query . " ORDER BY created_at";
        $result = $this->_mysql_connector->executeQuery($query);
        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = Image::constructFromRecord($row);
        }
        return $images;
    }

    public function createImage(): Image {
        $new_image = new Image();
        $new_image->setPublished(false);
        $new_image->setTitle('Nieuwe afbeelding');
        $new_image->setCreatedById(Authenticator::getCurrentUser()->getId());
        $this->persistImage($new_image);
        return $new_image;
    }

    public function deleteImage($image): void {
        $query = "DELETE FROM images WHERE id = " . $image->getId();

        // delete the uploaded images
        if (!is_null($image->getFileName()) && $image->getFileName() != '') {
            $file_path = UPLOAD_DIR . "/" . $image->getFileName();
            $thumb_file_path = UPLOAD_DIR . "/" . $image->getThumbFileName();
            if (file_exists($file_path))
                unlink($file_path);
            if (file_exists($thumb_file_path))
                unlink($thumb_file_path);
        }
        $this->_mysql_connector->executeQuery($query);
    }

    private function persistImage($image): void {
        $query = "INSERT INTO images (title, published, created_at, created_by, file_name, thumb_file_name)
                      VALUES ('" . $image->getTitle() . "', " . ($image->isPublished() ? 1 : 0) . ", now(), " .
            $image->getCreatedBy()->getId() . ", NULL, NULL)";
        $this->_mysql_connector->executeQuery($query);
        $image->setId($this->_mysql_connector->getInsertId());
    }

    public function createLabel(): ImageLabel {
        $new_label = new ImageLabel();
        $new_label->setName("Nieuw label");
        $postfix = 1;
        while (!is_null($this->getLabelByName($new_label->getName()))) {
            $new_label->setName("Nieuw label " . $postfix);
            $postfix++;
        }
        $new_id = $this->persistLabel($new_label);
        $new_label->setId($new_id);

        return $new_label;
    }

    public function getAllLabels(): array {
        $query = "SELECT * FROM image_labels";
        $result = $this->_mysql_connector->executeQuery($query);
        $labels = array();
        while ($row = $result->fetch_assoc()) {
            $labels[] = ImageLabel::constructFromRecord($row);
        }
        return $labels;
    }

    public function getLabel(int $id): ?ImageLabel {
        $query = "SELECT * FROM image_labels WHERE id = " . $id;
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ImageLabel::constructFromRecord($row);
        }
        return null;
    }

    public function getLabelByName(string $name): ?ImageLabel {
        $query = "SELECT * FROM image_labels WHERE name = '" . $name . "'";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ImageLabel::constructFromRecord($row);
        }
        return null;
    }

    public function persistLabel(ImageLabel $label): string {
        $query = "INSERT INTO image_labels (name) VALUES  ('" . $label->getName() . "')";
        $this->_mysql_connector->executeQuery($query);
        return $this->_mysql_connector->getInsertId();
    }

    public function updateLabel(ImageLabel $label): void {
        $query = "UPDATE image_labels SET name = '" . $label->getName() .
            "' WHERE id = " . $label->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function deleteLabel(ImageLabel $label): void {
        $query = "DELETE FROM image_labels WHERE id = " . $label->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function addLabelToImage(int $label_id, Image $image): void {
        $query = "INSERT INTO images_labels (image_id, label_id) VALUES (" . $image->getId() . ", " . $label_id . ")";
        $this->_mysql_connector->executeQuery($query);
    }

    public function deleteLabelForImage(int $label_id, Image $image): void {
        $query = "DELETE FROM images_labels WHERE image_id = " . $image->getId() . "
                      AND label_id = " . $label_id;
        $this->_mysql_connector->executeQuery($query);
    }

    public function getLabelsForImage(int $image_id): array {
        $query = "SELECT il.id, il.name FROM image_labels il, images_labels ils, 
                      images i WHERE ils.image_id = " . $image_id . " AND ils.image_id =
                      i.id AND il.id = ils.label_id";
        $result = $this->_mysql_connector->executeQuery($query);
        $labels = array();
        while ($row = $result->fetch_assoc()) {
            $labels[] = ImageLabel::constructFromRecord($row);
        }

        return $labels;
    }
}