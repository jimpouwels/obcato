<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "/core/data/element.php";
	require_once CMS_ROOT . "/database/mysql_connector.php";
	require_once CMS_ROOT . "/database/dao/article_dao.php";
	require_once CMS_ROOT . "/elements/article_overview_element/visuals/article_overview_element_statics.php";
	require_once CMS_ROOT . "/elements/article_overview_element/visuals/article_overview_element_editor.php";
    require_once CMS_ROOT . "/elements/article_overview_element/article_overview_element_request_handler.php";
    require_once CMS_ROOT . "/frontend/article_overview_element_visual.php";

	class ArticleOverviewElement extends Element {
			
		private $_title;
		private $_show_from;
		private $_show_to;
		private $_show_until_today;
		private $_order_by;
		private $_number_of_results;
		private $_term_ids;
		private $_add_term_ids;
		private $_remove_term_ids;
		private $_metadata_provider;
			
		public function __construct() {
			// set all text element specific metadata
			$this->_metadata_provider = new ArticleOverviewElementMetaDataProvider();
			$this->_add_term_ids = array();
			$this->_remove_term_ids = array();
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
		
		public function getTerms() {
			$article_dao = ArticleDao::getInstance();
			$terms = array();
			foreach ($this->_term_ids as $term_id) {
				array_push($terms, $article_dao->getTerm($term_id));
			}
			return $terms;
		}
		
		public function getTermIds() {
			return $this->_term_ids;
		}
		
		public function setTermIds($term_ids) {
			$this->_term_ids = $term_ids;
		}
		
		public function addTerm($term_id) {
			array_push($this->_add_term_ids, $term_id);
		}
		
		public function getAddTermIds() {
			return $this->_add_term_ids;
		}
		
		public function removeTerm($term_id) {
			array_push($this->_remove_term_ids, $term_id);
		}
	
		public function getRemoveTermIds() {
			return $this->_remove_term_ids;
		}
		
		public function getArticles() {
			include_once CMS_ROOT . "/database/dao/article_dao.php";
			include_once CMS_ROOT . "/libraries/utilities/date_utility.php";
			$article_dao = ArticleDao::getInstance();
			$_show_to = null;
			if ($this->_show_until_today != 1) {
				$_show_to = DateUtility::mysqlDateToString($this->_show_to, '-');
			}
			$articles = $article_dao->searchPublishedArticles(DateUtility::mysqlDateToString($this->_show_from, '-'),
															 $_show_to, $this->_order_by, $this->_term_ids, 
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
		
		/*
			Sets the meta data to the given element.
			
			@param $element The element to set the metadata for
		*/
		public function getMetaData($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT title, show_from, show_to, show_until_today, order_by, number_of_results FROM article_overview_elements_metadata " . 
					 "WHERE element_id = " . $element->getId();
			$result = $mysql_database->executeQuery($query);
			while ($row = $result->fetch_assoc()) {
				$element->setTitle($row['title']);
				$element->setShowFrom($row['show_from']);
				$element->setShowTo($row['show_to']);
				$element->setShowUntilToday($row['show_until_today'] == 1 ? true : false);
				$element->setOrderBy($row['order_by']);
				$element->setNumberOfResults($row['number_of_results']);
			}
			
			$element->setTermIds($this->getTerms($element));
		}
		
		/*
			Returns the terms for the given element.
			
			@param $element The element to get the terms for
		*/
		public function getTerms($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM articles_element_terms WHERE element_id = " . $element->getId();
			$result = $mysql_database->executeQuery($query);
			$term_ids = array();
			while ($row = $result->fetch_assoc()) {
				array_push($term_ids, $row['term_id']);
			}
			return $term_ids;
		}
		
		/*
			Updates the metadata for the given element.
			
			@param $element The element to update the metadata for
		*/
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
				$query = $query . " show_until_today = " . $element->getShowUntilToday() . ", order_by = '" . $element->getOrderBy() .
						 "' WHERE element_id = " . $element->getId();
			} else {
				$query = "INSERT INTO article_overview_elements_metadata (title, show_from, show_to, show_until_today, order_by, element_id, number_of_results) VALUES 
				          ('" . $element->getTitle() . "', NULL, NULL, 1, 'DATE', " . $element->getId() . ", NULL)"; 
			}
			$mysql_database->executeQuery($query);
			$this->addTerms($element);
			$this->removeTerms($element);
		}
		
		/*
			Removes the terms that have been selected for deletion for the given element.
			
			@param $element The element to delete terms for
		*/
		private function removeTerms($element) {
			$terms_ids_to_delete = $element->getRemoveTermIds();
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			foreach ($terms_ids_to_delete as $term_id) {
				$query = "DELETE FROM articles_element_terms WHERE element_id = " . $element->getId() . " AND term_id = " . $term_id;
				$mysql_database->executeQuery($query);	
			}
		}
		
		/*
			Adds the terms that have been selected for adding for the given element.
			
			@param $element The element to add terms for
		*/
		private function addTerms($element) {
			$terms_ids_to_add = $element->getAddTermIds();
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			foreach ($terms_ids_to_add as $term_id) {
				$query = "INSERT INTO articles_element_terms (element_id, term_id) VALUES (" . $element->getId() . ", " . $term_id . ")";
				$mysql_database->executeQuery($query);	
			}
		}
		
		/*
			Checks if the metadata for the given element is persisted.
			
			@param $element The element to check
		*/
		private function metaDataPersisted($element) {
			$query = "SELECT t.id, e.id FROM article_overview_elements_metadata t, elements e WHERE t.element_id = " 
					. $element->getId() . " AND e.id = " . $element->getId();
			$mysql_database = MysqlConnector::getInstance(); 
			$result = $mysql_database->executeQuery($query);
			while ($row = $result->fetch_assoc()) {
				return true;
			}
			return false;
		}
		
	}
	
?>