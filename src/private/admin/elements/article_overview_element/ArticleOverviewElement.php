<?php

use Obcato\ComponentApi\Visual;

require_once CMS_ROOT . "/core/model/Element.php";
require_once CMS_ROOT . "/core/model/ElementMetadataProvider.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";
require_once CMS_ROOT . "/elements/article_overview_element/visuals/ArticleOverviewElementStatics.php";
require_once CMS_ROOT . "/elements/article_overview_element/visuals/ArticleOverviewElementEditor.php";
require_once CMS_ROOT . "/elements/article_overview_element/ArticleOverviewElementRequestHandler.php";
require_once CMS_ROOT . "/frontend/ArticleOverviewElementFrontendVisual.php";

class ArticleOverviewElement extends Element {

    private ?string $showFrom = null;
    private ?string $showTo = null;
    private bool $showUntilToday = false;
    private ?string $orderBy = null;
    private ?string $orderType = null;
    private array $terms;
    private ?int $numberOfResults = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new ArticleOverviewElementMetadataProvider($this));
        $this->terms = array();
    }

    public function setShowFrom(?string $show_from): void {
        $this->showFrom = $show_from;
    }

    public function getShowFrom(): ?string {
        return $this->showFrom;
    }

    public function setShowTo(?string $show_to): void {
        $this->showTo = $show_to;
    }

    public function getShowTo(): ?string {
        return $this->showTo;
    }

    public function setShowUntilToday(bool $show_until_today): void {
        $this->showUntilToday = $show_until_today;
    }

    public function getShowUntilToday(): bool {
        return $this->showUntilToday;
    }

    public function setNumberOfResults(?int $number_of_results): void {
        $this->numberOfResults = $number_of_results;
    }

    public function getNumberOfResults(): ?int {
        return $this->numberOfResults;
    }

    public function setOrderBy(?string $order_by): void {
        $this->orderBy = $order_by;
    }

    public function getOrderBy(): ?string {
        return $this->orderBy;
    }

    public function setOrderType(?string $order_type): void {
        $this->orderType = $order_type;
    }

    public function getOrderType(): ?string {
        return $this->orderType;
    }

    public function addTerm(ArticleTerm $term): void {
        $this->terms[] = $term;
    }

    public function removeTerm(ArticleTerm $term): void {
        if (($key = array_search($term, $this->terms, true)) !== false) {
            unset($this->terms[$key]);
        }
    }

    public function setTerms(array $terms): void {
        $this->terms = $terms;
    }

    public function getTerms(): array {
        return $this->terms;
    }

    public function getArticles(): array {
        $article_dao = ArticleDaoMysql::getInstance();
        $show_to = null;
        if ($this->showUntilToday != 1 && $this->showTo) {
            $show_to = DateUtility::mysqlDateToString($this->showTo, '-');
        }
        $show_from = null;
        if ($this->showFrom) {
            $show_from = DateUtility::mysqlDateToString($this->showFrom, '-');
        }
        $articles = $article_dao->searchPublishedArticles($show_from,
            $show_to, $this->orderBy, $this->getOrderType(), $this->terms,
            $this->numberOfResults);
        return $articles;
    }

    public function getStatics(): Visual {
        return new ArticleOverviewElementStatics(TemplateEngine::getInstance());
    }

    public function getBackendVisual(): ElementVisual {
        return new ArticleOverviewElementEditor(TemplateEngine::getInstance(), $this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
        return new ArticleOverviewElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new ArticleOverviewElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summary_text = $this->getTitle() ?? "";
        if ($this->getTerms()) {
            $summary_text .= " (Termen:";
            foreach ($this->getTerms() as $term) {
                $summary_text .= " " . $term->getName();
            }
            $summary_text .= ")";
        }
        return $summary_text;
    }

}

class ArticleOverviewElementMetadataProvider extends ElementMetadataProvider {

    private ArticleDao $_article_dao;
    private Element $_element;
    private MysqlConnector $_mysql_connector;

    public function __construct($element) {
        parent::__construct($element);
        $this->_element = $element;
        $this->_article_dao = ArticleDaoMysql::getInstance();
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "article_overview_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void {
        $element->setTitle($record['title']);
        $element->setShowFrom($record['show_from']);
        $element->setShowTo($record['show_to']);
        $element->setOrderBy($record['order_by']);
        $element->setOrderType($record['order_type']);
        $element->setNumberOfResults($record['number_of_results']);
        $element->setTerms($this->getTerms());
    }

    public function update(Element $element): void {
        $query = "UPDATE article_overview_elements_metadata SET title = '" . $element->getTitle() . "', ";
        if ($element->getShowFrom() == '') {
            $query = $query . "show_from = NULL, ";
        } else {
            $query = $query . "show_from = '" . $element->getShowFrom() . "',";
        }
        if ($element->getShowTo() == '') {
            $query = $query . "show_to = NULL, ";
        } else {
            $query = $query . "show_to = '" . $element->getShowTo() . "',";
        }
        if ($element->getNumberOfResults() == '') {
            $query = $query . "number_of_results = NULL, ";
        } else {
            $query = $query . "number_of_results = " . $element->getNumberOfResults() . ",";
        }
        $query = $query . " order_by = '" . $element->getOrderBy() . "', order_type = '" . $element->getOrderType() .
            "' WHERE element_id = " . $element->getId();

        $this->_mysql_connector->executeQuery($query);
        $this->addTerms();
    }

    public function insert(Element $element): void {
        $query = "INSERT INTO article_overview_elements_metadata (title, show_from, show_to, order_by, order_type, element_id, number_of_results) VALUES
                        ('" . $element->getTitle() . "', NULL, NULL, 'PublicationDate', 'asc', " . $element->getId() . ", NULL)";
        $this->_mysql_connector->executeQuery($query);
        $this->addTerms();
    }

    private function getTerms(): array {
        $query = "SELECT * FROM articles_element_terms WHERE element_id = " . $this->_element->getId();
        $result = $this->_mysql_connector->executeQuery($query);
        $terms = array();
        while ($row = $result->fetch_assoc()) {
            array_push($terms, $this->_article_dao->getTerm($row['term_id']));
        }
        return $terms;
    }

    private function addTerms(): void {
        $existing_terms = $this->getTerms();
        foreach ($existing_terms as $existing_term) {
            if (!in_array($existing_term, $this->_element->getTerms())) {
                $this->removeTerm($existing_term);
            }
        }
        foreach ($this->_element->getTerms() as $term) {
            if (!in_array($term, $existing_terms)) {
                $statement = $this->_mysql_connector->prepareStatement("INSERT INTO articles_element_terms (element_id, term_id) VALUES (?, ?)");
                $term_id = $term->getId();
                $element_id = $this->_element->getId();
                $statement->bind_param('ii', $element_id, $term_id);
                $this->_mysql_connector->executeStatement($statement);
            }
        }
    }

    private function removeTerm(ArticleTerm $term): void {
        $statement = $this->_mysql_connector->prepareStatement("DELETE FROM articles_element_terms WHERE element_id = ? AND term_id = ?");
        $elementId = $this->_element->getId();
        $termId = $term->getId();
        $statement->bind_param('ii', $elementId, $termId);
        $this->_mysql_connector->executeStatement($statement);
    }

}

?>