<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class TableOfContentsElement extends Element {

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new TableOfContentsElementMetadataProvider($this));
    }

    public function getStatics(): Visual {
        return new TableOfContentsElementStatics(TemplateEngine::getInstance());
    }

    public function getBackendVisual(): ElementVisual {
        return new TableOfContentsElementEditor(TemplateEngine::getInstance(), $this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
        return new TableOfContentsElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new TableOfContentsElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        return $this->getTitle() ?? "";
    }

}

class TableOfContentsElementMetadataProvider extends ElementMetadataProvider {

    private MysqlConnector $mysqlConnector;

    public function __construct(TableOfContentsElement $element) {
        parent::__construct($element);
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "table_of_contents_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void {
        $element->setTitle($record['title']);
    }

    public function update(Element $element): void {
        $query = "UPDATE table_of_contents_elements_metadata SET title = '" . $element->getTitle() . "'";
        $this->mysqlConnector->executeQuery($query);
    }

    public function insert(Element $element): void {
        $query = "INSERT INTO table_of_contents_elements_metadata (title, element_id) VALUES ('" . $element->getTitle() . "', " . $element->getId() . ")";
        $this->mysqlConnector->executeQuery($query);
    }

}

?>