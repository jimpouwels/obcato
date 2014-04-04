<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "database/mysql_connector.php";
	include_once FRONTEND_REQUEST . "database/dao/element_dao.php";
	include_once FRONTEND_REQUEST . "database/dao/element_holder_dao.php";
	include_once FRONTEND_REQUEST . "core/data/article.php";
	include_once FRONTEND_REQUEST . "core/data/article_term.php";
	include_once FRONTEND_REQUEST . "database/dao/authorization_dao.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/date_utility.php";
	
	class ArticleDao {

		private static $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id, 
					  e.created_at, e.created_by, e.type, a.description, a.image_id, a.publication_date, a.target_page";

		private static $instance;
		private $_page_dao;
		private $_element_holder_dao;

		private function __construct() {
			$this->_page_dao = PageDao::getInstance();
			$this->_element_holder_dao = ElementHolderDao::getInstance();
		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ArticleDao();
			}
			return self::$instance;
		}

		public function getArticle($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, articles a WHERE e.id = " . $id
					 . " AND e.id = a.element_holder_id";
			if (FRONTEND_REQUEST != '') {
				$query = $query . " AND e.published = 1";
			}
			
			$result = $mysql_database->executeSelectQuery($query);
			$article = null;
			
			while ($row = mysql_fetch_array($result)) {
				$article = Article::constructFromRecord($row);
			}
			return $article;
		}

		public function getAllArticles() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, articles a WHERE e.id = a.element_holder_id
					  order by created_at DESC";
			$result = $mysql_database->executeSelectQuery($query);
			$articles = array();
			
			while ($row = mysql_fetch_array($result)) {
				$article = Article::constructFromRecord($row);
				
				array_push($articles, $article);
			}
			
			return $articles;
		}

		public function searchArticles($keyword, $term_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$from = " FROM element_holders e, articles a";
			$where = " WHERE
					  e.id = a.element_holder_id";
			
			if (!is_null($keyword) && $keyword != '') {
				$where = $where . " AND e.title LIKE '" . $keyword . "%'";
			}
			if (!is_null($term_id)) {
				$from = $from . ", articles_terms ats";
				$where = $where . " AND ats.term_id = " . $term_id . " AND ats.article_id = e.id";
			}
			
			$query = "SELECT DISTINCT " . self::$myAllColumns . $from . $where . " ORDER BY created_at";
			$result = $mysql_database->executeSelectQuery($query);
			$articles = array();
			while ($row = mysql_fetch_array($result)) {
				$article = Article::constructFromRecord($row);
				
				array_push($articles, $article);
			}
			
			return $articles;
		}

		public function searchArticlesFrontend($from_date, $to_date, $order_by, $terms, $max_results) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$from = " FROM element_holders e, articles a";
			$where = " WHERE
					  e.id = a.element_holder_id";
			$order = '';
			$limit = '';
			
			$where = $where . " AND published = 1";
			$where = $where . " AND publication_date <= now()";
			if (!is_null($to_date) && $to_date != '') {
				$where = $where . " AND publication_date <= '" . DateUtility::stringMySqlDate($to_date) . "'";
			}
			if (!is_null($from_date) && $from_date != '') {
				$where = $where . " AND publication_date > '" . DateUtility::stringMySqlDate($from_date) . "'";
			}
			if (!is_null($terms) && count($terms) > 0) {
				$from = $from . ", articles_terms at";
				foreach ($terms as $term) {
					$where = $where . " AND EXISTS(SELECT * FROM articles_terms at WHERE at.article_id = e.id AND at.term_id = " . $term . ")";
				}
			}
			if (!is_null($max_results) && $max_results != '') {
				$limit = " LIMIT " . $max_results;
			}
			
			if (!is_null($order_by) && $order_by != '') {
				if ($order_by == 'Alfabet') {
					$order = 'e.title';
				} else {
					$order = 'a.publication_date DESC';
				}
			}
			
			$query = "SELECT DISTINCT " . self::$myAllColumns . $from . $where . " ORDER BY " . $order . $limit;
			$result = $mysql_database->executeSelectQuery($query);
			$articles = array();
			while ($row = mysql_fetch_array($result)) {
				$article = Article::constructFromRecord($row);
				
				array_push($articles, $article);
			}
			
			return $articles;
		}

		public function updateArticle($article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE articles SET description = '" . $article->getDescription() . "'";
						if (!is_null($article->getImageId()) && $article->getImageId() != '') {
				$query = $query . ", image_id = " . $article->getImageId();
			} else {
				$query = $query . ", image_id = NULL";
			}
			if (!is_null($article->getTargetPageId()) && $article->getTargetPageId() != '') {
				$query = $query . ", target_page = " . $article->getTargetPageId();
			} else {
				$query = $query . ", target_page = NULL";
			}
			$query = $query . " WHERE element_holder_id = " . $article->getId();
			$mysql_database->executeQuery($query);
			$this->_element_holder_dao->update($article);
		}

		public function deleteArticle($article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM element_holders WHERE id = " . $article->getId();
			
			$element_dao = ElementDao::getInstance();
			foreach ($article->getElements() as $element) {
				$element_dao->deleteElement($element);
			}
			
			$mysql_database->executeQuery($query);
		}

		public function createArticle() {
			$new_article = new Article();
			$mysql_database = MysqlConnector::getInstance(); 
			$new_article->setPublished(false);
			$new_article->setTitle('Nieuw artikel');
			
			$authorization_dao = AuthorizationDao::getInstance();
			$user = $authorization_dao->getUser($_SESSION["username"]);
			$new_article->setCreatedById($user->getId());
			$new_article->setType(ELEMENT_HOLDER_ARTICLE);
			
			$new_id = $this->persistArticle($new_article);
			$new_article->setId($new_id);
			
			return $new_article;
		}

		private function persistArticle($article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$published_value = $article->isPublished();
			if (!isset($published_value) || $published_value == '') {
				$published_value = 0;
			}
			$query1 = "INSERT INTO element_holders (template_id, title, published, scope_id, created_at, created_by, type)
					   VALUES  (NULL, '" . $article->getTitle() . "', " . $published_value . ",
					   " . $article->getScopeId() . ", now(), " . $article->getCreatedBy()->getId() . ", '" . $article->getType() . "')";
		
			
			$mysql_database->executeQuery($query1);
			
			$new_id = mysql_insert_id();
			
			$query2 = "INSERT INTO articles (description, image_id, element_holder_id, publication_date, target_page) VALUES 
					  (NULL, NULL, " . $new_id . ", now(), NULL)";
			
			$mysql_database->executeQuery($query2);
			
			return $new_id;
		}

		public function getAllTerms() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM article_terms";
			$result = $mysql_database->executeSelectQuery($query);
			$terms = array();
			
			while ($row = mysql_fetch_array($result)) {
				$term = new ArticleTerm();
				$term->setId($row['id']);
				$term->setName($row['name']);
				
				array_push($terms, $term);
			}
			
			return $terms;
		}

		public function getTerm($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM article_terms WHERE id = " . $id;
			$result = $mysql_database->executeSelectQuery($query);
			$term = NULL;
			
			while ($row = mysql_fetch_array($result)) {
				$term = new ArticleTerm();
				$term->setId($row['id']);
				$term->setName($row['name']);
			}
			
			return $term;
		}

		public function getTermByName($name) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM article_terms WHERE name = '" . $name . "'";
			$result = $mysql_database->executeSelectQuery($query);
			$term = NULL;
			
			while ($row = mysql_fetch_array($result)) {
				$term = new ArticleTerm();
				$term->setId($row['id']);
				$term->setName($row['name']);
			}
			
			return $term;
		}

		public function createTerm() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_term = new ArticleTerm();
			$new_term->setName('Nieuwe term');
			
			$new_id = $this->persistTerm($new_term);
			
			$new_term->setId($new_id);
			
			return $new_term;
		}

		private function persistTerm($term) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$query = "INSERT INTO article_terms (name) VALUES  ('" . $term->getName() . "')";
		
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}

		public function updateTerm($term) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE article_terms SET name = '" . $term->getName() . 
					  "' WHERE id = " . $term->getId();
			$mysql_database->executeQuery($query);
		}

		public function deleteTerm($term) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM article_terms WHERE id = " . $term->getId();
			
			$mysql_database->executeQuery($query);
		}

		public function getTermsForArticle($article_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT at.id, at.name FROM article_terms at, articles_terms ats, 
					  element_holders e WHERE ats.article_id = " . $article_id . " AND ats.article_id =
					  e.id AND at.id = ats.term_id";
					  
		    $result = $mysql_database->executeSelectQuery($query);
			$terms = array();
			
			while ($row = mysql_fetch_array($result)) {
				$term = new ArticleTerm();
				$term->setId($row['id']);
				$term->setName($row['name']);
				
				array_push($terms, $term);
			}
			
			return $terms;
		}

		public function addTermToArticle($term_id, $article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO articles_terms (article_id, term_id) VALUES (" . $article->getId() . ", " . $term_id . ")";
			
			$mysql_database->executeQuery($query);
		}

		public function deleteTermFromArticle($term_id, $article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$query = "DELETE FROM articles_terms WHERE article_id = " . $article->getId() ."
			          AND term_id = " . $term_id;
			
			$mysql_database->executeQuery($query);
		}

		public function getTargetPages() {
			$mysql_database = MysqlConnector::getInstance();
			$query = "SELECT element_holder_id FROM article_target_pages";			
			$result = $mysql_database->executeSelectQuery($query);
			$pages = array();
			while ($row = mysql_fetch_assoc($result)) {
				$pages[] = $this->_page_dao->getPage($row['element_holder_id']);
			}
			
			return $pages;
		}

		public function addTargetPage($target_page_id) {
			$mysql_database = MysqlConnector::getInstance();
			
			$duplicate_check_query = "SELECT count(*) AS number_of FROM article_target_pages WHERE element_holder_id = " . $target_page_id;
			$result = $mysql_database->executeSelectQuery($duplicate_check_query);
			while ($row = mysql_fetch_assoc($result)) {
				$count = $row['number_of'];
				break;
			}
			
			if ($count == 0) {
				$query = "INSERT INTO article_target_pages (element_holder_id, is_default) VALUES (" . $target_page_id . ", 0)";
				$mysql_database->executeQuery($query);
				
				// check if only one target page is present
				$this->updateDefaultArticleTargetPage();
			}
		}	

		public function deleteTargetPage($target_page_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query = "DELETE FROM article_target_pages where element_holder_id = " . $target_page_id;
			$mysql_database->executeQuery($query);
			
			// check if only one target page is present
			$this->updateDefaultArticleTargetPage();
		}

		public function getDefaultTargetPage() {
			$mysql_database = MysqlConnector::getInstance();
			$query = "SELECT element_holder_id FROM article_target_pages WHERE is_default = 1";			
			$result = $mysql_database->executeSelectQuery($query);
			$page = null;
			while ($row = mysql_fetch_assoc($result)) {
				$page = Page::findById($row['element_holder_id']);
				break;
			}
			
			return $page;
		}

		public function setDefaultArticleTargetPage($target_page_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query1 = "UPDATE article_target_pages SET is_default = 0 WHERE is_default = 1";	
			$query2 = "UPDATE article_target_pages SET is_default = 1 WHERE element_holder_id = " . $target_page_id;
			
			$mysql_database->executeQuery($query1);
			$mysql_database->executeQuery($query2);
		}

		private function updateDefaultArticleTargetPage() {
			$target_pages = $this->getTargetPages();
			if (!is_null($target_pages) && count($target_pages) == 1) {		
				$mysql_database = MysqlConnector::getInstance();
				$query = "UPDATE article_target_pages SET is_default = 1";
				$mysql_database->executeQuery($query);
			}
		}
		
	}
	
?>