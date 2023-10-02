<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/element.php";
require_once CMS_ROOT . "/core/model/element_metadata_provider.php";
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";
require_once CMS_ROOT . "/elements/image_element/visuals/image_element_statics.php";
require_once CMS_ROOT . "/elements/image_element/visuals/image_element_editor.php";
require_once CMS_ROOT . "/elements/image_element/image_element_request_handler.php";
require_once CMS_ROOT . "/frontend/image_element_visual.php";

class ImageElement extends Element {

    private ?string $_align = null;
    private ?int $_height = null;
    private ?int $_width = null;
    private ?int $_image_id = null;

    public function __construct(int $scope_id) {
        parent::__construct($scope_id, new ImageElementMetadataProvider($this));
    }

    public function setAlign(?string $align): void {
        $this->_align = $align;
    }

    public function getAlign(): ?string {
        return $this->_align;
    }

    public function getWidth(): ?int {
        return $this->_width;
    }

    public function setWidth(?int $width): void {
        $this->_width = $width;
    }

    public function getHeight(): ?int {
        return $this->_height;
    }

    public function setHeight(?int $height): void {
        $this->_height = $height;
    }

    public function setImageId(?int $image_id): void {
        $this->_image_id = $image_id;
    }

    public function getImageId(): ?int {
        return $this->_image_id;
    }

    public function getImage(): ?Image {
        $image = null;
        if ($this->_image_id != null) {
            $image_dao = ImageDaoMysql::getInstance();
            $image = $image_dao->getImage($this->_image_id);
        }
        return $image;
    }

    public function getStatics(): Visual {
        return new ImageElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new ImageElementEditorVisual($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): ImageElementFrontendVisual {
        return new ImageElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new ImageElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summary_text = $this->getTitle() ?: '';
        $image = $this->getImage();
        if ($image) {
            $summary_text .= ': ' . $image->getTitle() . ' (' . $image->getFileName() . ')';
        }
        return $summary_text;
    }
}

class ImageElementMetadataProvider extends ElementMetadataProvider {

    private MysqlConnector $_mysql_connector;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "image_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void {
        $element->setTitle($record['title']);
        $element->setAlign($record['align']);
        $element->setImageId($record['image_id']);
        $element->setWidth($record['width']);
        $element->setHeight($record['height']);
    }

    public function update(Element $element): void {
        $image_id = $element->getImageId();
        $title = $element->getTitle();
        $align = $element->getAlign();
        $width = $element->getWidth();
        $height = $element->getHeight();
        $element_id = $element->getId();
        $query = "UPDATE image_elements_metadata SET title = ?, align = ?, image_id = ?, width = ?, height = ? WHERE element_id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('ssiiii', $title, $align, $image_id, $width, $height, $element_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function insert(Element $element): void {
        $title = $element->getTitle();
        $element_id = $element->getId();
        $query = "INSERT INTO image_elements_metadata (title, align, width, height, image_id, element_id) VALUES (?, NULL, 0 , 0, NULL, ?)";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('si', $title, $element_id);
        $this->_mysql_connector->executeStatement($statement);
    }

}

?>
