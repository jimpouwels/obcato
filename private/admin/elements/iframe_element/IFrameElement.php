<?php
require_once CMS_ROOT . "/core/model/Element.php";
require_once CMS_ROOT . "/core/model/ElementMetadataProvider.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/elements/iframe_element/visuals/IFrameElementStatics.php";
require_once CMS_ROOT . "/elements/iframe_element/visuals/IFrameElementEditor.php";
require_once CMS_ROOT . "/elements/iframe_element/IFrameElementRequestHandler.php";
require_once CMS_ROOT . "/frontend/IFrameElementFrontendVisual.php";

class IFrameElement extends Element {

    private ?string $_url = null;
    private ?int $_width = null;
    private ?int $_height = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new IFrameElementMetadataProvider($this));
    }

    public function setUrl(?string $url): void {
        $this->_url = $url;
    }

    public function getUrl(): ?string {
        return $this->_url;
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

    private MysqlConnector $_mysql_connector;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->_mysql_connector = MysqlConnector::getInstance();
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

        $this->_mysql_connector->executeQuery($query);
    }

    public function insert(Element $element): void {
        $query = "INSERT INTO iframe_elements_metadata (title, `url`, width, height, element_id) VALUES "
            . "('" . $element->getTitle() . "', '" . $element->getUrl() . "', 0 , 0, " . $element->getId() . ")";
        $this->_mysql_connector->executeQuery($query);
    }

}

?>