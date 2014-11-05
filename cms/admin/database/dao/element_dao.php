<?php

	
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "database/mysql_connector.php";
	include_once CMS_ROOT . "core/data/element_type.php";
	include_once CMS_ROOT . "core/data/element.php";

	class ElementDao {
	
		private static $myAllColumns = "e.id, e.follow_up, e.template_id, t.classname, t.identifier, 
										t.domain_object, e.element_holder_id";
	
		private static $instance;
		
		private function __construct() {
		}
		
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ElementDao();
			}
			return self::$instance;
		}
		
		public function getElements($element_holder) {
			$mysql_database = MysqlConnector::getInstance(); 
			$elements_info_query = "SELECT " . self::$myAllColumns . " FROM elements e, element_types t WHERE element_holder_id 
									= " . $element_holder->getId() . " AND t.id = e.type_id ORDER BY e.follow_up ASC";
			$result = $mysql_database->executeQuery($elements_info_query);
			$elements = array();
			while ($row = $result->fetch_assoc()) {		
				$element = Element::constructFromRecord($row);
				
				array_push($elements, $element);
			}
			return $elements;
		}
		
		public function getElement($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$element = NULL;
			$query = "SELECT " . self::$myAllColumns . " FROM elements e, element_types t WHERE e.id = " . $id . " 
					  AND e.type_id = t.id;";
			$result = $mysql_database->executeQuery($query);
			while ($row = $result->fetch_assoc()) {
				$element = Element::constructFromRecord($row);
			}
			return $element;
		}
		
		public function updateElement($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$set = 'follow_up = ' . $element->getIndex();
			if (!is_null($element->getTemplateId()) && $element->getTemplateId() != '') {
				$set = $set . ', template_id = ' . $element->getTemplateId();
			}
			$query = "UPDATE elements SET " . $set . "	WHERE id = " . $element->getId();
			$mysql_database->executeQuery($query);
			$element->updateMetaData();
		}
		
		public function deleteElement($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM elements WHERE id = " . $element->getId();
			$mysql_database->executeQuery($query);
		}
		
		public function getElementTypes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types ORDER BY name";
			$result = $mysql_database->executeQuery($query);
			$element_types = array();
			$element_type = NULL;
			while ($row = $result->fetch_assoc()) {
				$element_type = ElementType::constructFromRecord($row);
				
				array_push($element_types, $element_type);
			}
			
			return $element_types;
		}
		
		public function updateElementType($element_type) {
			$mysql_database = MysqlConnector::getInstance();
			
			$system_default_val = 0;
			if ($element_type->getSystemDefault()) {
				$system_default_val = 1;
			}
			$query = "UPDATE element_types SET classname = '" . $element_type->getClassName() . "', icon_url = '" . $element_type->getIconUrl() . "', name = '" .
					  $element_type->getName() . "', domain_object = '" . $element_type->getDomainObject() . "', scope_id = " . 
					  $element_type->getScopeId() . ", identifier = '" . $element_type->getIdentifier() . "', system_default = " . 
					  $system_default_val . " WHERE id = " . $element_type->getId();
					  
			$mysql_database->executeQuery($query);
		}
		
		public function persistElementType($element_type) {
			$mysql_database = MysqlConnector::getInstance();

			$system_default_val = 0;
			if ($element_type->getSystemDefault()) {
				$system_default_val = 1;
			}
			$query = "INSERT INTO element_types (classname, icon_url, name, domain_object, scope_id, identifier, system_default)" .
					 " VALUES ('" . $element_type->getClassName() . "', '" . $element_type->getIconUrl() .
					 "', '" . $element_type->getName() . "', '" . $element_type->getDomainObject() . "', " . $element_type->getScopeId() . ", " . 
					 "'" . $element_type->getIdentifier() . "', " . $system_default_val . ")";
			$mysql_database->executeQuery($query);
			
			return $mysql_database->getInsertId();
		}
		
		public function deleteElementType($element_type) {
			$mysql_database = MysqlConnector::getInstance();
			$query = "DELETE FROM element_types WHERE id = " . $element_type->getId();
			$mysql_database->executeQuery($query);
		}
		
		public function getDefaultElementTypes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types WHERE system_default = 1 ORDER BY name";
			$result = $mysql_database->executeQuery($query);
			$element_types = array();
			$element_type = NULL;
			while ($row = $result->fetch_assoc()) {
				$element_type = ElementType::constructFromRecord($row);
				
				array_push($element_types, $element_type);
			}
			
			return $element_types;
		}
		
		public function getCustomElementTypes() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types WHERE system_default = 0 ORDER BY name";
			$result = $mysql_database->executeQuery($query);
			$element_types = array();
			$element_type = NULL;
			while ($row = $result->fetch_assoc()) {
				$element_type = ElementType::constructFromRecord($row);
				
				array_push($element_types, $element_type);
			}
			
			return $element_types;
		}
		
		public function getElementType($element_type_id) {
			$mysql_database = MysqlConnector::getInstance();
			$query = "SELECT * FROM element_types WHERE id = " . $element_type_id;
			$result = $mysql_database->executeQuery($query);
			$element_type = NULL;
			while ($row = $result->fetch_assoc()) {
				$element_type = ElementType::constructFromRecord($row);
				
				break;
			}
			
			return $element_type;
		}
		
		public function getElementTypeByIdentifier($identifier) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types WHERE identifier = '$identifier'";
			$result = $mysql_database->executeQuery($query);
			$element_type = null;
			while ($row = $result->fetch_assoc()) {
				$element_type = ElementType::constructFromRecord($row);
				
				break;
			}
			
			return $element_type;
		}

		public function getElementTypeForElement($element_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM element_types t, elements e WHERE e.id = " . $element_id . " AND t.id = e.type_id";
			$result = $mysql_database->executeQuery($query);
			$element_type = null;
			while ($row = $result->fetch_assoc()) {
				$element_type = ElementType::constructFromRecord($row);
				
				break;
			}
			
			return $element_type;
		}

		public function createElement($element_type, $element_holder_id) {
			$element_location_base = "";
			if (!$element_type->getSystemDefault()) {
				$element_location_base = COMPONENT_DIR . "/";
			}
			include_once CMS_ROOT . $element_location_base . "elements/" . $element_type->getIdentifier() . "/" . $element_type->getDomainObject();
			$element_classname = $element_type->getClassName();
			$new_element = new $element_classname;
	
			$new_element->setIndex($this->getNextElementIndex($element_holder_id));
			$new_element->setScopeId($element_type->getScopeId());
			$this->persistElement($element_type, $new_element, $element_holder_id);
			return $new_element;
		}
		
		private function persistElement($element_type, $element, $element_holder_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO elements(follow_up,type_id, element_holder_id, template_id) VALUES (" . $element->getIndex() . " 
			          , " . $element_type->getId() . ", " . $element_holder_id . ", 0)";
			$mysql_database->executeQuery($query);
			$element->setId($mysql_database->getInsertId());
			$element->updateMetaData();
		}
		
		private function getNextElementIndex($element_holder_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "SELECT max(follow_up) AS next_available_index FROM elements WHERE element_holder_id = " . $element_holder_id;
			
			$result = $mysql_database->executeQuery($query);
			$next_index = NULL;
			while ($row = $result->fetch_assoc()) {
				$next_index = $row['next_available_index'];
				if (is_null($next_index)) {
					$next_index = 0;
				} else {
					$next_index = $next_index + 1;
				}
				
			}
			
			return $next_index;
		}
		
		static function updateElementOrder($element_order, $element_holder) {
			$element_ids = array();
			$element_ids = explode(',', $element_order);
			if (count($element_ids) > 0 && $element_ids[0] != '') {
				include_once CMS_ROOT . "database/dao/element_dao.php";
				
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