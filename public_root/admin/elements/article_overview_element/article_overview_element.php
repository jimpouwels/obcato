<?php

    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/data/element.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "elements/article_overview_element/visuals/article_overview_element_statics.php";
    require_once CMS_ROOT . "elements/article_overview_element/visuals/article_overview_element_editor.php";
    require_once CMS_ROOT . "elements/article_overview_element/article_overview_element_request_handler.php";
    require_once CMS_ROOT . "frontend/article_overview_element_visual.php";

    class ArticleOverviewElement extends Element {
            
        private $_title;
        private $_show_from;
        private $_show_to;
        private $_show_until_today;
        private $_order_by;
        private $_order_type;
        private $_terms;
        private $_number_of_results;
        private $_metadata_provider;
            
        public function __construct() {
            // set all text element specific metadata
            $this->_terms = array();
            $this->_metadata_provider = new ArticleOverviewElementMetaDataProvider($this);
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function getTitle() {
            return $this->_title;
        }
        
        public function setShowFrom($show_from) {
            $this->_show_from = $show_from;
        }
        
        public function getShowFrom() {
            return $this->_show_from;
        }
        
        public function setShowTo($show_to) {
            $this->_show_to = $show_to;
        }
        
        public function getShowTo() {
            return $this->_show_to;
        }
        
        public function setShowUntilToday($show_until_today) {
            $this->_show_until_today = $show_until_today;
        }
        
        public function getShowUntilToday() {
            return $this->_show_until_today;
        }
        
        public function setNumberOfResults($number_of_results) {
            $this->_number_of_results = $number_of_results;
        }
        
        public function getNumberOfResults() {
            return $this->_number_of_results;
        }

        public function setOrderBy($order_by) {
            $this->_order_by = $order_by;
        }

        public function getOrderBy() {
            return $this->_order_by;
        }

        public function setOrderType($order_type) {
            $this->_order_type = $order_type;
        }

        public function getOrderType() {
            return $this->_order_type;
        }

        public function addTerm($term) {
            $this->_terms[] = $term;
        }

        public function removeTerm($term) {
            if(($key = array_search($term, $this->_terms, true)) !== false)
                unset($this->_terms[$key]);
        }

        public function setTerms($terms) {
            $this->_terms = $terms;
        }
        
        public function getTerms() {
            return $this->_terms;
        }
        
        public function getArticles() {
            include_once CMS_ROOT . "database/dao/article_dao.php";
            include_once CMS_ROOT . "utilities/date_utility.php";
            $article_dao = ArticleDao::getInstance();
            $_show_to = null;
            if ($this->_show_until_today != 1)
                $_show_to = DateUtility::mysqlDateToString($this->_show_to, '-');
            $articles = $article_dao->searchPublishedArticles(DateUtility::mysqlDateToString($this->_show_from, '-'),
                                                             $_show_to, $this->_order_by, $this->getOrderType(), $this->_terms,
                                                             $this->_number_of_results);
            return $articles;
        }
        
        public function getStatics() {
            return new ArticleOverviewElementStatics();
        }
        
        public function getBackendVisual() {
            return new ArticleOverviewElementEditor($this);
        }

        public function getFrontendVisual($current_page) {
            return new ArticleOverviewElementFrontendVisual($current_page, $this);
        }
        
        public function initializeMetaData() {
            $this->_metadata_provider->getMetaData($this);
        }
        
        public function updateMetaData() {
            $this->_metadata_provider->updateMetaData($this);
        }

        public function getRequestHandler() {
            return new ArticleOverviewElementRequestHandler($this);
        }
    }
    
    class ArticleOverviewElementMetaDataProvider {

        private $_article_dao;
        private $_element;

        public function __construct($element) {
            $this->_element = $element;
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function getMetaData($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT * FROM article_overview_elements_metadata " . "WHERE element_id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $element->setTitle($row['title']);
                $element->setShowFrom($row['show_from']);
                $element->setShowTo($row['show_to']);
                $element->setOrderBy($row['order_by']);
                $element->setOrderType($row['order_type']);
                $element->setNumberOfResults($row['number_of_results']);
            }
            
            $element->setTerms($this->getTerms());
        }

        private function getTerms() {
            $mysql_database = MysqlConnector::getInstance(); 
            $query = "SELECT * FROM articles_element_terms WHERE element_id = " . $this->_element->getId();
            $result = $mysql_database->executeQuery($query);
            $terms = array();
            while ($row = $result->fetch_assoc())
                array_push($terms, $this->_article_dao->getTerm($row['term_id']));
            return $terms;
        }

        public function updateMetaData($element) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            if ($this->metaDataPersisted($element)) {
                $query = "UPDATE article_overview_elements_metadata SET title = '" . $element->getTitle() . "', ";
                if (is_null($element->getShowFrom()) || $element->getShowFrom() == '') {
                    $query = $query . "show_from = NULL, ";
                } else {
                    $query = $query . "show_from = '" . $element->getShowFrom() . "',";
                } if (is_null($element->getShowTo()) || $element->getShowTo() == '') {
                    $query = $query . "show_to = NULL, ";
                } else {
                    $query = $query . "show_to = '" . $element->getShowTo() . "',";
                }
                if (is_null($element->getNumberOfResults()) || $element->getNumberOfResults() == '') {
                    $query = $query . "number_of_results = NULL, ";
                } else {
                    $query = $query . "number_of_results = " . $element->getNumberOfResults() . ",";
                }
                $query = $query . " order_by = '" . $element->getOrderBy() . "', order_type = '" . $element->getOrderType() .
                         "' WHERE element_id = " . $element->getId();
            } else {
                $query = "INSERT INTO article_overview_elements_metadata (title, show_from, show_to, order_by, order_type, element_id, number_of_results) VALUES
                          ('" . $element->getTitle() . "', NULL, NULL, 'PublicationDate', 'asc', " . $element->getId() . ", NULL)";
            }
            $mysql_database->executeQuery($query);
            $this->addTerms();
        }

        private function addTerms() {
            $existing_terms = $this->getTerms();
            foreach ($existing_terms as $existing_term) {
                if (!in_array($existing_term, $this->_element->getTerms()))
                    $this->removeTerm($existing_term);
            }
            foreach ($this->_element->getTerms() as $term) {
                if (!in_array($term, $existing_terms)) {
                    $mysql_database = MysqlConnector::getInstance();
                    $statement = $mysql_database->prepareStatement("INSERT INTO articles_element_terms (element_id, term_id) VALUES (?, ?)");
                    $term_id = $term->getId();
                    $statement->bind_param('ii', $this->_element->getId(), $term_id);
                    $mysql_database->executeStatement($statement);
                }
            }
        }

        private function removeTerm($term) {
            $mysql_database = MysqlConnector::getInstance();
            $statement = $mysql_database->prepareStatement("DELETE FROM articles_element_terms WHERE element_id = ? AND term_id = ?");
            $statement->bind_param('ii', $this->_element->getId(), $term->getId());
            $mysql_database->executeStatement($statement);
        }

        private function metaDataPersisted($element) {
            $query = "SELECT t.id, e.id FROM article_overview_elements_metadata t, elements e WHERE t.element_id = " 
                    . $element->getId() . " AND e.id = " . $element->getId();
            $mysql_database = MysqlConnector::getInstance(); 
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc())
                return true;
            return false;
        }
        
    }
    
?>