<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/system/mysql_connector.php";
	include_once FRONTEND_REQUEST . "dao/element_dao.php";
	include_once FRONTEND_REQUEST . "dao/block_dao.php";
	include_once FRONTEND_REQUEST . "dao/template_dao.php";
	include_once FRONTEND_REQUEST . "core/data/page.php";
	include_once FRONTEND_REQUEST . "dao/authorization_dao.php";

	class PageDao {
	
		// Holds the list of columns that are to be collected
		private static $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id, 
					  e.created_at, e.created_by, e.type, p.navigation_title, p.description, p.parent_id, p.show_in_navigation,
					  p.include_in_searchindex, p.follow_up, p.is_homepage";
	
		/*
			This DAO is a singleton, no constructur but
			a getInstance() method instead.
		*/
		private static $instance;
		private $_mysql_connector;
		
		/*
			Private constructor.
		*/
		private function __construct() {
			$this->_mysql_connector = MysqlConnector::getInstance();
		}
		
		/*
			Creates (if not exists) and returns an instance.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new PageDao();
			}
			return self::$instance;
		}
		
		/*
			Returns the page with the given ID.
			
			@param $id The ID of the page to find
		*/
		public function getPage($id) {
			$query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE e.id = " . $id . "
					  AND e.id = p.element_holder_id";
			if (FRONTEND_REQUEST != '') {
				$query = $query . " AND e.published = 1";
			}
			$result = $this->_mysql_connector->executeSelectQuery($query);
			$page = NULL;
			while ($row = mysql_fetch_array($result)) {
				$page = Page::constructFromRecord($row);
				break;
			}
			return $page;
		}
		
		/*
			Returns all root pages (which should be only the homepage).
		*/
		public function getRootPages() {
			$query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE p.parent_id IS NULL
			          AND e.id = p.element_holder_id";
			$result = $this->_mysql_connector->executeSelectQuery($query);
			$pages = array();
			while ($row = mysql_fetch_assoc($result)) {
				$page = Page::constructFromRecord($row);
				
				array_push($pages, $page);
			}

			return $pages;
		}
		
		/*
			Returns all subpages of the given page.
			
			@param $page The page where the sub pages should be found for
			@param $published If true: Only the published sub pages are 
							  returned. If false: All sub pages are returned
		*/
		public function getSubPages($page) {
			$query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE p.parent_id = " . $page->getId() . 
					  " AND p.element_holder_id = e.id";
			if (FRONTEND_REQUEST != '') {
				$query = $query . ' AND published = 1';
			}
			
			$query = $query . " ORDER BY p.follow_up";
			
			$result = $this->_mysql_connector->executeSelectQuery($query);
			$pages = array();
			while ($row = mysql_fetch_assoc($result)) {
				$page = Page::constructFromRecord($row);
				
				$pages[] = $page;
			}
			
			return $pages;
		}
		
		/*
			Persist the given page.
			
			@param $page The page to persist
		*/
		public function persist($page) {
			$query = "INSERT INTO pages (navigation_title, parent_id, show_in_navigation, include_in_searchindex, element_holder_id,
					 follow_up, is_homepage, description) VALUES ('" . $page->getNavigationTitle() . "', " . $page->getParentId() . "," 
					 . $page->getShowInNavigation() . ", 1, " . $page->getId() . ", 1, 0, '')";
			$this->_mysql_connector->executeQuery($query);
		}
		
		/*
			Updates the given page object.
			
			@param @page The page to update
		*/
		public function updatePage($page) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE pages SET navigation_title = '" . $page->getNavigationTitle() . "', show_in_navigation = " . $page->getShowInNavigation() . ", 
					include_in_searchindex = " . $page->getIncludeInSearchEngine() . ", follow_up = " . $page->getFollowUp() . ", description = '" . 
					  $page->getDescription() . "' WHERE element_holder_id = " . $page->getId();
			
			if ($page->getParentId() != '' && !is_null($page->getParentId())) {
				$query = $query . ", parent_id = " . $page->getParentId();
			}
			$this->_mysql_connector->executeQuery($query);
		}
		
		/*
			Searches for pages with the given title.
			
			@param $term The term to search for
		*/
		public function searchByTerm($term) {
			$query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE e.id = p.element_holder_id 
			          AND title like '" . $term . "%'";
			$result = $this->_mysql_connector->executeSelectQuery($query);
			$pages = array();
			while ($row = mysql_fetch_assoc($result)) {
				$page = Page::constructFromRecord($row);
				
				$pages[] = $page;
			}
			return $pages;
		}
		
		/*
			Moves the given page up.
			
			@param $page The page to move up
		*/
		public function moveUp($page) {
			$query1 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE follow_up = " . ($page->getFollowUp() - 1);
			$query2 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE element_holder_id = " . $page->getId();
			
			$this->_mysql_connector->executeQuery($query1);
			$this->_mysql_connector->executeQuery($query2);
		}
		
		/*
			Moves the given page down.
			
			@param $page The page to move down
		*/
		public function moveDown($page) {
			$query1 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE follow_up = " . ($page->getFollowUp() + 1);
			$query2 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE element_holder_id = " . $page->getId();
			$this->_mysql_connector->executeQuery($query1);
			$this->_mysql_connector->executeQuery($query2);
		}
	}
?>