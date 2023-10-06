<?php
require_once CMS_ROOT . "/core/model/Element.php";
require_once CMS_ROOT . "/core/model/ElementMetadataProvider.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/elements/table_of_contents_element/visuals/TableOfContentsElementStatics.php";
require_once CMS_ROOT . "/elements/table_of_contents_element/visuals/TableOfContentsElementEditor.php";
require_once CMS_ROOT . "/elements/table_of_contents_element/TableOfContentsElementRequestHandler.php";
require_once CMS_ROOT . "/frontend/TableOfContentsElementFrontendVisual.php";

class TableOfContentsElement extends Element {

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new TableOfContentsElementMetadataProvider($this));
    }

    public function getStatics(): Visual {
        return new TableOfContentsElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new TableOfContentsElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
        return new TableOfContentsElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new TableOfContentsElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        return $this->getTitle();
    }

}

class TableOfContentsElementMetadataProvider extends ElementMetadataProvider {

    private MysqlConnector $_mysql_connector;

    public function __construct(TableOfContentsElement $element) {
        parent::__construct($element);
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "table_of_contents_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void {
        $element->setTitle($record['title']);
    }

    public function update(Element $element): void {
        $query = "UPDATE table_of_contents_elements_metadata SET title = '" . $element->getTitle() . "'";
        $this->_mysql_connector->executeQuery($query);
    }

    public function insert(Element $element): void {
        $query = "INSERT INTO table_of_contents_elements_metadata (title, element_id) VALUES ('" . $element->getTitle() . "', " . $element->getId() . ")";
        $this->_mysql_connector->executeQuery($query);
    }

}

?>