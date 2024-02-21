<?php

namespace Obcato\Core\admin\elements\iframe_element;

use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\core\model\Element;
use Obcato\Core\admin\core\model\ElementMetadataProvider;
use Obcato\Core\admin\database\MysqlConnector;
use Obcato\Core\admin\elements\iframe_element\visuals\IFrameElementEditor;
use Obcato\Core\admin\elements\iframe_element\visuals\IFrameElementStatics;
use Obcato\Core\admin\frontend\IFrameElementFrontendVisual;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;
use Obcato\Core\admin\view\TemplateEngine;
use Obcato\Core\admin\view\views\ElementVisual;

class IFrameElement extends Element {

    private ?string $url = null;
    private ?int $width = null;
    private ?int $height = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new IFrameElementMetadataProvider($this));
    }

    public function setUrl(?string $url): void {
        $this->url = $url;
    }

    public function getUrl(): ?string {
        return $this->url;
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

    public function getStatics(): Visual {
        return new IFrameElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new IFrameElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): IFrameElementFrontendVisual {
        return new IFrameElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new IFrameElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        return "IFrame";
    }
}

class IFrameElementMetadataProvider extends ElementMetadataProvider {

    private MysqlConnector $mysqlConnector;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "iframe_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void {
        $element->setTitle($record['title']);
        $element->setUrl($record['url']);
        $element->setWidth($record['width']);
        $element->setHeight($record['height']);
    }

    public function update(Element $element): void {
        $query = "UPDATE iframe_elements_metadata SET `url` = '" . $element->getUrl() . "', title = '" . $element->getTitle() . "', width = " . ($element->getWidth() ? $element->getWidth() : "NULL") . ", height = " . ($element->getHeight() ? $element->getHeight() : "NULL") .
            " WHERE element_id = " . $element->getId();

        $this->mysqlConnector->executeQuery($query);
    }

    public function insert(Element $element): void {
        $query = "INSERT INTO iframe_elements_metadata (title, `url`, width, height, element_id) VALUES "
            . "('" . $element->getTitle() . "', '" . $element->getUrl() . "', 0 , 0, " . $element->getId() . ")";
        $this->mysqlConnector->executeQuery($query);
    }

}