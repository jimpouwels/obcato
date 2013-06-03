<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/system/mysql_connector.php";
	include_once FRONTEND_REQUEST . "dao/element_dao.php";
	include_once FRONTEND_REQUEST . "core/data/article.php";
	include_once FRONTEND_REQUEST . "core/data/article_term.php";
	include_once FRONTEND_REQUEST . "dao/authorization_dao.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/date_utility.php";
	
	class ArticleDao {
	
		// Holds the list of columns that are to be collected
		private static $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id, 
					  e.created_at, e.created_by, e.type, a.description, a.image_id, a.publication_date, a.target_page";
	
		/*
			This DAO is a singleton, no constructur but
			a getInstance() method instead.
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates (if not exists) and returns an instance.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ArticleDao();
			}
			return self::$instance;
		}
		
		/*
			Returns the article with the given ID
			
			@param $id The ID of the article to find
		*/
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
		
		/*
			Returns all articles.
		*/
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
		
		/*
			Returns all articles that match the given keyword.
			
			@param $keyword The keyword to search for
			@param $term_id The term to search for
		*/
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
		
		/*
			Returns all articles that match the given parameters.
			
			@param $from_date The date from to find articles for
			@param $to_date The date to to find articles for
			@param $order_by Order by
			@param $terms The terms the article must have
		*/
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
		
		/*
			Updates the given article.
			
			@param $article The article to update
		*/
		public function updateArticle($article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE articles a, element_holders e SET e.title = '" . $article->getTitle() . "'
					 , e.published = " . $article->isPublished() . ", a.description = '" . $article->getDescription() . "',
					 publication_date = '" . $article->getPublicationDate() . "'";
			
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
			$query = $query . " WHERE e.id = " . $article->getId() . " AND e.id = a.element_holder_id";
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the given article.
			
			@param $article The article to delete
		*/
		public function deleteArticle($article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM element_holders WHERE id = " . $article->getId();
			
			$element_dao = ElementDao::getInstance();
			foreach ($article->getElements() as $element) {
				$element_dao->deleteElement($element);
			}
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Creates a new article.
		*/
		public function createArticle() {
			$new_article = new Article();
			$mysql_database = MysqlConnector::getInstance(); 
			$new_article->setPublished(false);
			$new_article->setTitle('Nieuw artikel');
			
			$authorization_dao = AuthorizationDao::getInstance();
			$user = $authorization_dao->getUser($_SESSION['username']);
			$new_article->setCreatedById($user->getId());
			$new_article->setType(ELEMENT_HOLDER_ARTICLE);
			
			$new_id = $this->persistArticle($new_article);
			$new_article->setId($new_id);
			
			return $new_article;
		}
		
		/*
			Persists the given article.
			
			@param $article The article to persist
		*/
		private function persistArticle($article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$published_value = $article->isPublished();
			if (!isset($published_value) || $published_value == '') {
				$published_value = 0;
			}
			$query1 = "INSERT INTO element_holders (template_id, title, published, scope_id, created_at, created_by, type)
					   VALUES  (NULL, '" . $article->getTitle() . "', " . $published_value . ",
					   NULL, now(), " . $article->getCreatedBy()->getId() . ", '" . $article->getType() . "')";
		
			
			$mysql_database->executeQuery($query1);
			
			$new_id = mysql_insert_id();
			
			$query2 = "INSERT INTO articles (description, image_id, element_holder_id, publication_date, target_page) VALUES 
					  (NULL, NULL, " . $new_id . ", now(), NULL)";
			
			$mysql_database->executeQuery($query2);
			
			return $new_id;
		}
		
		/*
			Returns all terms.
		*/
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
		
		/*
			Returns the term with the given ID.
			
			@param $id The ID to find the term for
		*/
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
		
		/*
			Returns the term with the given name.
			
			@param $name The name to find the term for
		*/
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
		
		/*
			Creates and persists a new term.
		*/
		public function createTerm() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_term = new ArticleTerm();
			$new_term->setName('Nieuwe term');
			
			$new_id = $this->persistTerm($new_term);
			
			$new_term->setId($new_id);
			
			return $new_term;
		}
		
		/*
			Persists the given term.
			
			@param $term The term to update
		*/
		private function persistTerm($term) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$query = "INSERT INTO article_terms (name) VALUES  ('" . $term->getName() . "')";
		
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
		
		/*
			Updates the given term.
			
			@param $term The term to update
		*/
		public function updateTerm($term) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE article_terms SET name = '" . $term->getName() . 
					  "' WHERE id = " . $term->getId();
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the term with the given ID.
			
			@param $term_id The ID of the term
				   to update
		*/
		public function deleteTerm($term) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM article_terms WHERE id = " . $term->getId();
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Returns all terms related to the given article ID.
			
			@param $article_id The ID of the article to find the terms for
		*/
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
		
		/*
			Adds the given term to the given article.
			
			@param $term_id The ID of the term to add to the article
			@param $article The article to add the term to
		*/
		public function addTermToArticle($term_id, $article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO articles_terms (article_id, term_id) VALUES (" . $article->getId() . ", " . $term_id . ")";
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the term with the given ID from the given article.
			
			@param $term_id The ID of the term to delete from the article
			@param $article The article to delete the term from
		*/
		public function deleteTermFromArticle($term_id, $article) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$query = "DELETE FROM articles_terms WHERE article_id = " . $article->getId() ."
			          AND term_id = " . $term_id;
			
			$mysql_database->executeQuery($query);
		}
				
		/*
			Returns all configured target pages.
		*/
		public function getTargetPages() {
			$mysql_database = MysqlConnector::getInstance();
			$query = "SELECT element_holder_id FROM article_target_pages";			
			$result = $mysql_database->executeSelectQuery($query);
			$pages = array();
			while ($row = mysql_fetch_assoc($result)) {
				$pages[] = Page::findById($row['element_holder_id']);
			}
			
			return $pages;
		}
		
		/*
			Adds the given page as a target page for aticles.
			
			@param $target_page The page to add as target page for articles
		*/
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
		
		/*
			Deletes the given page as a target page for articles.
			
			@param $target_page The page to delete as target page for articles
		*/
		public function deleteTargetPage($target_page_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query = "DELETE FROM article_target_pages where element_holder_id = " . $target_page_id;
			$mysql_database->executeQuery($query);
			
			// check if only one target page is present
			$this->updateDefaultArticleTargetPage();
		}
		
		/*
			Returns the default article target page.
		*/
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
		
		/*
			Sets the default article target page to the given ID.
			
			@param $target_page_id The default article target page to set
		*/
		public function setDefaultArticleTargetPage($target_page_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query1 = "UPDATE article_target_pages SET is_default = 0 WHERE is_default = 1";	
			$query2 = "UPDATE article_target_pages SET is_default = 1 WHERE element_holder_id = " . $target_page_id;
			
			$mysql_database->executeQuery($query1);
			$mysql_database->executeQuery($query2);
		}
		
		/*
			Checks if there is only one article target page, and makes that default.
		*/
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