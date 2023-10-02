<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/Element.php";
require_once CMS_ROOT . "/core/model/ElementMetadataProvider.php";
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/utilities/date_utility.php";
require_once CMS_ROOT . "/elements/article_overview_element/visuals/article_overview_element_statics.php";
require_once CMS_ROOT . "/elements/article_overview_element/visuals/article_overview_element_editor.php";
require_once CMS_ROOT . "/elements/article_overview_element/article_overview_element_request_handler.php";
require_once CMS_ROOT . "/frontend/article_overview_element_visual.php";

class ArticleOverviewElement extends Element {

    private ?string $_show_from = null;
    private ?string $_show_to = null;
    private bool $_show_until_today = false;
    private ?string $_order_by = null;
    private ?string $_order_type = null;
    private array $_terms;
    private ?int $_number_of_results = null;

    public function __construct(int $scope_id) {
        parent::__construct($scope_id, new ArticleOverviewElementMetadataProvider($this));
        $this->_terms = array();
    }

    public function setShowFrom(?string $show_from): void {
        $this->_show_from = $show_from;
    }

    public function getShowFrom(): ?string {
        return $this->_show_from;
    }

    public function setShowTo(?string $show_to): void {
        $this->_show_to = $show_to;
    }

    public function getShowTo(): ?string {
        return $this->_show_to;
    }

    public function setShowUntilToday(bool $show_until_today): void {
        $this->_show_until_today = $show_until_today;
    }

    public function getShowUntilToday(): bool {
        return $this->_show_until_today;
    }

    public function setNumberOfResults(?int $number_of_results): void {
        $this->_number_of_results = $number_of_results;
    }

    public function getNumberOfResults(): ?int {
        return $this->_number_of_results;
    }

    public function setOrderBy(?string $order_by): void {
        $this->_order_by = $order_by;
    }

    public function getOrderBy(): ?string {
        return $this->_order_by;
    }

    public function setOrderType(?string $order_type): void {
        $this->_order_type = $order_type;
    }

    public function getOrderType(): ?string {
        return $this->_order_type;
    }

    public function addTerm(ArticleTerm $term): void {
        $this->_terms[] = $term;
    }

    public function removeTerm(ArticleTerm $term): void {
        if (($key = array_search($term, $this->_terms, true)) !== false) {
            unset($this->_terms[$key]);
        }
    }

    public function setTerms(array $terms): void {
        $this->_terms = $terms;
    }

    public function getTerms(): array {
        return $this->_terms;
    }

    public function getArticles(): array {
        $article_dao = ArticleDaoMysql::getInstance();
        $show_to = null;
        if ($this->_show_until_today != 1 && $this->_show_to) {
            $show_to = DateUtility::mysqlDateToString($this->_show_to, '-');
        }
        $show_from = null;
        if ($this->_show_from) {
            $show_from = DateUtility::mysqlDateToString($this->_show_from, '-');
        }
        $articles = $article_dao->searchPublishedArticles($show_from,
            $show_to, $this->_order_by, $this->getOrderType(), $this->_terms,
            $this->_number_of_results);
        return $articles;
    }

    public function getStatics(): Visual {
        return new ArticleOverviewElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new ArticleOverviewElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
        return new ArticleOverviewElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new ArticleOverviewElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summary_text = $this->getTitle();
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