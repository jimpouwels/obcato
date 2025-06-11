<?php

namespace Obcato\Core\elements\article_overview_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\modules\articles\model\ArticleTerm;

class ArticleOverviewElementMetadataProvider extends ElementMetadataProvider
{

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
        $element->setSiblingsOnly($record['siblings_only']);
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
        $query = $query . " order_by = '" . $element->getOrderBy() . "', order_type = '" . $element->getOrderType() . "', siblings_only = " . ($element->getSiblingsOnly() ? 1 : 0) .
            " WHERE element_id = " . $element->getId();

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