<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "database/mysql_connector.php";
	include_once CMS_ROOT . "core/data/element_holder.php";

	class ElementHolderDao {
	
		// Holds the list of columns that are to be collected
		private static $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id, 
					  e.created_at, e.created_by, e.type";
	
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
				self::$instance = new ElementHolderDao();
			}
			return self::$instance;
		}
		
		/*
			Returns the element holder with the given ID.
			
			@param $id The ID of the element holder to find
		*/
		public function getElementHolder($id) {
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e WHERE e.id = " . $id;
			$result = $this->_mysql_connector->executeSelectQuery($query);
			$element_holder = NULL;
			while ($row = mysql_fetch_array($result)) {
				$element_holder = ElementHolder::constructFromRecord($row);

				break;
			}
			return $element_holder;
		}
		
		/*
			Persists the element holder.
			
			@param $element_holder The element holder to persist
		*/
		public function persist($element_holder) {
			$query = "INSERT INTO element_holders (template_id, title, published, scope_id, created_at, created_by, type) VALUES
					  (NULL, '" . $element_holder->getTitle() . "', 0," . $element_holder->getScopeId() . ", now(), " . $element_holder->getCreatedBy()->getId() . "
					  , '" . $element_holder->getType() . "')";
		   
			$this->_mysql_connector->executeQuery($query);
			$element_holder->setId(mysql_insert_id());
		}
		
		/*
			Updates the element holder.
			
			@param $element_holder The element holder to update
		*/
		public function update($element_holder) {
			$query = "UPDATE element_holders SET title = '" . $element_holder->getTitle() . "', published = " . $element_holder->isPublished() . ",
					  scope_id = " . $element_holder->getScopeId();
			if ($element_holder->getTemplateId() != "" && !is_null($element_holder->getTemplateId())) {
				$query .= ", template_id = " . $element_holder->getTemplateId();
			}
			$query .= " WHERE id = " . $element_holder->getId();
			$this->_mysql_connector->executeQuery($query);
		}
		
		/*
			Deletes the element holder.
			
			@param $element_holder The element holder to delete
		*/
		public function delete($element_holder) {
			$query = "DELETE FROM element_holders WHERE id = " . $element_holder->getId();				  
			$this->_mysql_connector->executeQuery($query);
		}
	}
?>