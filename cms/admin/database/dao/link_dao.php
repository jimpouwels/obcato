<?php

	
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "database/mysql_connector.php";
	include_once CMS_ROOT . "core/data/link.php";

	class LinkDao {
	
		// singleton
		private static $instance;
		
		// Holds the list of columns that are to be collected
		private static $myAllColumns = "id, title, target_address, type, code, parent_element_holder, target_element_holder";
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates and returns a new instance of the DAO
			if it not yet exists.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new LinkDao();
			}
			return self::$instance;
		}
		
		/*
			Creates and persists a new link.
			
			@param $element_holder_id The element holder to add the link to
		*/
		public function createLink($element_holder_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_link = new Link();
			$new_link->setTitle('Nieuwe link');
			$new_link->setCode($new_link->getId());
			$new_link->setParentElementHolderId($element_holder_id);
			$new_link->setType(Link::INTERNAL);
			
			$new_id = $this->persistLink($new_link);
			$new_link->setId($new_id);
			
			return $new_link;
		}
		
		/*
			Persists the given link.
			
			@param $new_link The page to persist
		*/
		public function persistLink($new_link) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO links (title, target_address, type, code, target_element_holder, parent_element_holder) 
					  VALUES ('" . $new_link->getTitle() . "', NULL, '" . $new_link->getType() . 
					  "', '" . $new_link->getCode() . "', NULL, " . $new_link->getParentElementHolderId() . ")";

			$mysql_database->executeQuery($query);
			
			return $mysql_database->getInsertId();
		}
		
		/*
			Returns all links for the given element holder.
			
			@param $element_holder_id The element holder ID to find the links for
		*/
		public function getLinksForElementHolder($element_holder_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM links WHERE parent_element_holder = " . $element_holder_id;
			$result = $mysql_database->executeQuery($query);
			$links = array();
			while ($row = $result->fetch_assoc()) {
				$link = Link::constructFromRecord($row);
				
				$links[] = $link;
			}
			return $links;
		}
		
		/*
			Deletes the given link.
			
			@param $link The link to delete
		*/
		public function deleteLink($link) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM links WHERE id = " . $link->getId();
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Updates the given link object.
			
			@param @link The link to update
		*/
		public function updateLink($link) {
			$mysql_database = MysqlConnector::getInstance(); 
						
			if (!is_null($link->getTargetElementHolder()) && $link->getTargetElementHolder() != '') {
				$link->setType(Link::INTERNAL);
			} else {
				$link->setType(Link::EXTERNAL);
			}
			
			$query = "UPDATE links SET title = '" . $link->getTitle() . "', target_address = '" . $link->getTargetAddress() . "',
					  code = '" . $link->getCode() . "', type = '" . $link->getType() . "'";
			
			if ($link->getTargetElementHolderId() != '' && !is_null($link->getTargetElementHolderId())) {
				$query = $query . ", target_element_holder = " . $link->getTargetElementHolderId();
			} else {
				$query = $query . ", target_element_holder = NULL";
			}
			$query = $query . " WHERE id = " . $link->getId();
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Returns a list of links withouth a target.
		*/
		public function getBrokenLinks() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM links WHERE target_address IS NULL AND target_element_holder IS NULL";
			$result = $mysql_database->executeQuery($query);
			$links = array();
			while ($row = $result->fetch_assoc()) {
				$link = Link::constructFromRecord($row);
				
				$links[] = $link;
			}
			return $links;			
		}
		
	}
?>