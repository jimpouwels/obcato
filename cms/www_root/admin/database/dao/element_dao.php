<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "database/mysql_connector.php";
	include_once FRONTEND_REQUEST . "core/data/element_type.php";
	include_once FRONTEND_REQUEST . "core/data/element.php";

	class ElementDao {
	
		// Holds the list of columns that are to be collected
		private static $myAllColumns = "e.id, e.follow_up, e.template_id, t.classname, t.identifier, 
										t.domain_object, e.element_holder_id, t.edit_presentation";
	
		/*
			Singleton
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates a new instance if it does not yet exist.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ElementDao();
			}
			return self::$instance;
		}
		
		/*
			Returns all elements for the given element holder.
			
			@param $element_holder The element holder to return
								   the elements for
		*/
		public function getElements($element_holder) {
			$mysql_database = MysqlConnector::getInstance(); 
			$elements_info_query = "SELECT " . self::$myAllColumns . " FROM elements e, element_types t WHERE element_holder_id 
									= " . $element_holder->getId() . " AND t.id = e.type_id ORDER BY e.follow_up ASC";
			$result = $mysql_database->executeSelectQuery($elements_info_query);
			$elements = array();
			while ($row = mysql_fetch_assoc($result)) {		
				$element = Element::constructFromRecord($row);
				
				array_push($elements, $element);
			}
			return $elements;
		}
		
		/*
			Returns an element by ID.
			
			@param $id The ID of the element to find
		*/
		public function getElement($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$element = NULL;
			$query = "SELECT " . self::$myAllColumns . " FROM elements e, element_types t WHERE e.id = " . $id . " 
					  AND e.type_id = t.id;";
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				$element = Element::constructFromRecord($row);
			}
			return $element;
		}
		
		/*
			Updates the given element.
			
			@param $element The element to update
		*/
		public function updateElement($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$set = 'follow_up = ' . $element->getIndex();
			if (!is_null($element->getTemplateId()) && $element->getTemplateId() != '') {
				$set = $set . ', template_id = ' . $element->getTemplateId();
			}
			$query = "UPDATE elements SET " . $set . "	WHERE id = " . $element->getId();
			$mysql_database->executeSelectQuery($query);
			$element->updateMetaData();
		}
		
		/*
			Deletes the given element.
			
			@param $element The element to delete
		*/
		public function deleteElement($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM elements WHERE id = " . $element->getId();
			$mysql_database->executeQuery($query);
		}
		
		/*
			Returns all element types.
		*/
		public function getElementTypes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types ORDER BY name";
			$result = $mysql_database->executeSelectQuery($query);
			$element_types = array();
			$element_type = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$element_type = ElementType::constructFromRecord($row);
				
				array_push($element_types, $element_type);
			}
			
			return $element_types;
		}
		
		/*
			Updates the given element type.
			
			@param $element_type the element type to update
		*/
		public function updateElementType($element_type) {
			$mysql_database = MysqlConnector::getInstance();
			
			$system_default_val = 0;
			if ($element_type->getSystemDefault()) {
				$system_default_val = 1;
			}
			$query = "UPDATE element_types SET classname = '" . $element_type->getClassName() . "', edit_presentation = '" . 
					  $element_type->getEditPresentation() . "', icon_url = '" . $element_type->getIconUrl() . "', name = '" . 
					  $element_type->getName() . "', domain_object = '" . $element_type->getDomainObject() . "', scope_id = " . 
					  $element_type->getScopeId() . ", identifier = '" . $element_type->getIdentifier() . "', system_default = " . 
					  $system_default_val . ", destroy_script = '" . $element_type->getDestroyScript() . "' WHERE id = " . $element_type->getId();
					  
			$mysql_database->executeQuery($query);
		}
		
		/*
			Persists the given element type.
			
			@param $element_type The element type to persist
		*/
		public function persistElementType($element_type) {
			$mysql_database = MysqlConnector::getInstance();

			$system_default_val = 0;
			if ($element_type->getSystemDefault()) {
				$system_default_val = 1;
			}
			$query = "INSERT INTO element_types (classname, edit_presentation, icon_url, name, domain_object, scope_id, identifier, system_default, destroy_script)" . 
					 " VALUES ('" . $element_type->getClassName() . "', '" . $element_type->getEditPresentation() . "', '" . $element_type->getIconUrl() . 
					 "', '" . $element_type->getName() . "', '" . $element_type->getDomainObject() . "', " . $element_type->getScopeId() . ", " . 
					 "'" . $element_type->getIdentifier() . "', " . $system_default_val . ", '" . $element_type->getDestroyScript() . "')";
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
		
		/*
			Deletes the element type with the given ID.
			
			@param $element_type_id The element type to delete
		*/
		public function deleteElementType($element_type_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query = "DELETE FROM element_types WHERE id = $element_type_id";
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Returns all default element types.
		*/
		public function getDefaultElementTypes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types WHERE system_default = 1 ORDER BY name";
			$result = $mysql_database->executeSelectQuery($query);
			$element_types = array();
			$element_type = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$element_type = ElementType::constructFromRecord($row);
				
				array_push($element_types, $element_type);
			}
			
			return $element_types;
		}
		
		/*
			Returns all custom element types.
		*/
		public function getCustomElementTypes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types WHERE system_default = 0 ORDER BY name";
			$result = $mysql_database->executeSelectQuery($query);
			$element_types = array();
			$element_type = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$element_type = ElementType::constructFromRecord($row);
				
				array_push($element_types, $element_type);
			}
			
			return $element_types;
		}
		
		/*
			Returns the element type with the given ID.
			
			@param $element_type_id The ID of the element type to find
		*/
		public function getElementType($element_type_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query = "SELECT * FROM element_types WHERE id = " . $element_type_id;
			$result = $mysql_database->executeSelectQuery($query);
			$element_type = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$element_type = ElementType::constructFromRecord($row);
				
				break;
			}
			
			return $element_type;
		}
		
		/*
			Returns the element type with the given identifier.
			
			@param $element_type_id The ID of the element type to find
		*/
		public function getElementTypeByIdentifier($identifier) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types WHERE identifier = '$identifier'";
			$result = $mysql_database->executeSelectQuery($query);
			$element_type = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$element_type = ElementType::constructFromRecord($row);
				
				break;
			}
			
			return $element_type;
		}
		
		/*
			Returns the element type for the given element ID.
			
			@param $element_id The ID of the element to find the type for
		*/
		public function getElementTypeForElement($element_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types t, elements e WHERE e.id = " . $element_id . " AND t.id = e.type_id";
			$result = $mysql_database->executeSelectQuery($query);
			$element_type = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$element_type = ElementType::constructFromRecord($row);
				
				break;
			}
			
			return $element_type;
		}
				
		/*
			Creates a new element.
			
			@param $element_type The type of element to create
			@param $element_holder_id The element holder where the new
									  element will belong to
		*/
		public function createElement($element_type, $element_holder_id) {
			$element_location_base = "";
			if (!$element_type->getSystemDefault()) {
				$element_location_base = COMPONENT_DIR . "/";
			}
			include_once $element_location_base . "elements/" . $element_type->getIdentifier() . "/" . $element_type->getDomainObject();
			$element_classname = $element_type->getClassName();
			$new_element = new $element_classname;
	
			$new_element->setIndex($this->getNextElementIndex($element_holder_id));
			$new_element->setScopeId($element_type->getScopeId());
			$this->persistElement($element_type, $new_element, $element_holder_id);
			return $new_element;
		}
		
		/*
			Persists the given element.
			
			@param $element_type The element type to persist for the element
			@param $element The element to persist
			@param $element_holder_id The element holder ID to persist the element for
		*/
		private function persistElement($element_type, $element, $element_holder_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO elements(follow_up,type_id, element_holder_id, template_id) VALUES (" . $element->getIndex() . " 
			          , " . $element_type->getId() . ", " . $element_holder_id . ", 0)";
			$mysql_database->executeQuery($query);
			$element->setId(mysql_insert_id());
			$element->updateMetaData();
		}
		
		/*
			Finds the next available index for an element.
		*/
		private function getNextElementIndex($element_holder_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT max(follow_up) AS next_available_index FROM elements WHERE element_holder_id = " . $element_holder_id;
			
			$result = $mysql_database->executeSelectQuery($query);
			$next_index = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$next_index = $row['next_available_index'];
				if (is_null($next_index)) {
					$next_index = 0;
				} else {
					$next_index = $next_index + 1;
				}
				
			}
			
			return $next_index;
		}
		
		
		/*
			Updates the order of the given elements for the given element holder.
			
			@param $element_order The new element order
			@param $element_holder The element holder to change the order of the elements for
		*/
		static function updateElementOrder($element_order, $element_holder) {
			$element_ids = array();
			$element_ids = explode(',', $element_order);
			if (count($element_ids) > 0 && $element_ids[0] != '') {
				include_once "database/dao/element_dao.php";
				
				$element_dao = ElementDao::getInstance();
				
				$i = 0;
				for ($counter = 0; $counter < count($element_ids); $counter += 1) {
					$element = $element_dao->getElement($element_ids[$counter]);
					$element->setIndex($counter);
					$element_dao->updateElement($element);
				}
			}
		}
		
	}
?>