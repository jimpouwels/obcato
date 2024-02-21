<?php

namespace Obcato\Core\admin\elements\image_element;

use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\core\model\Element;
use Obcato\Core\admin\core\model\ElementMetadataProvider;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\database\MysqlConnector;
use Obcato\Core\admin\elements\image_element\visuals\ImageElementEditor;
use Obcato\Core\admin\elements\image_element\visuals\ImageElementStatics;
use Obcato\Core\admin\frontend\ImageElementFrontendVisual;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\images\model\Image;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;
use Obcato\Core\admin\view\TemplateEngine;
use Obcato\Core\admin\view\views\ElementVisual;

class ImageElement extends Element {

    private ?string $align = null;
    private ?int $height = null;
    private ?int $width = null;
    private ?int $imageId = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new ImageElementMetadataProvider($this));
    }

    public function setAlign(?string $align): void {
        $this->align = $align;
    }

    public function getAlign(): ?string {
        return $this->align;
    }

    public function getWidth(): ?int {
        return $this->width;
    }

    public function setWidth(?int $width): void {
        $this->width = $width;
    }

    public function getHeight(): ?int {
        return $this->height;
    }

    public function setHeight(?int $height): void {
        $this->height = $height;
    }

    public function setImageId(?int $image_id): void {
        $this->imageId = $image_id;
    }

    public function getImageId(): ?int {
        return $this->imageId;
    }

    public function getImage(): ?Image {
        $image = null;
        if ($this->imageId != null) {
            $image_dao = ImageDaoMysql::getInstance();
            $image = $image_dao->getImage($this->imageId);
        }
        return $image;
    }

    public function getStatics(): Visual {
        return new ImageElementStatics(TemplateEngine::getInstance());
    }

    public function getBackendVisual(): ElementVisual {
        return new ImageElementEditor(TemplateEngine::getInstance(), $this);
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
            $summary_text .= ': ' . $image->getTitle() . ' (' . $image->getFilename() . ')';
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
