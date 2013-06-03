<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "libraries/system/mysql_connector.php";
	include_once FRONTEND_REQUEST . "core/data/template.php";

	/*
		This class takes care of all persistance actions for a Template object.
	*/
	class TemplateDao {
	
		/*
			This service is a singleton
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/* 
			Creates a new instance (if not yet exists
			for this DAO
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new TemplateDao();
			}
			return self::$instance;
		}
		
		/* 
			Returns a Template object for the given
			ID value
			
			@param The ID to Template to find
		*/
		public function getTemplate($id) {
			$template = NULL;
				if (!is_null($id)) {
				$mysql_database = MysqlConnector::getInstance(); 
			
				$query = "SELECT * FROM templates WHERE id = " . $id;
				$result = $mysql_database->executeSelectQuery($query);
				
				while ($row = mysql_fetch_assoc($result)) {
					$template = Template::constructFromRecord($row);
				}
			}
			
			return $template;
		}
		
		/* 
			Returns an array of Template objects
			by the given Template Scope.
			
			@param $scope The scope of Template objects
			       to find
		*/
		public function getTemplatesByScope($scope) {
			$templates = array();
			if (!is_null($scope) && $scope != '') {
				$mysql_database = MysqlConnector::getInstance(); 
			
				$query = "SELECT * FROM templates WHERE scope_id = '" . $scope->getId() . "'";
				$result = $mysql_database->executeSelectQuery($query);
				$template = NULL;
				while ($row = mysql_fetch_assoc($result)) {
					$template = Template::constructFromRecord($row);
					
					array_push($templates, $template);
				}
			}
			
			return $templates;
		}
		
		/* 
			Returns an array of Template objects
			by the given Template Scope.
		*/
		public function getTemplates() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM templates";
			$result = $mysql_database->executeSelectQuery($query);
			
			$template = NULL;
			$templates = array();
			while ($row = mysql_fetch_assoc($result)) {
				$template = Template::constructFromRecord($row);
				
				array_push($templates, $template);
			}
			
			return $templates;
		}
		
		/* 
			Creates a new Template object and persists
			it.
		*/
		public function createTemplate() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_template = new Template();
			$new_template->setScopeId(null);
			$new_template->setName('Nieuw template');
			$new_id = $this->persistTemplate($new_template);
			$new_template->setId($new_id);
			
			return $new_template;
		}
		
		/*
			Returns a template by filename.
			
			@param $file_name The file name to find the template for
		*/
		public function getTemplateByFileName($file_name) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM templates WHERE filename = '" . $file_name . "'";
			$result = $mysql_database->executeSelectQuery($query);
			$template = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$template = Template::constructFromRecord($row);
			}
			
			return $template;
		}
		
		/*
			Persists the given page.
			
			@param $new_template The template to persist
		*/
		public function persistTemplate($new_template) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO templates (filename, scope_id, name) VALUES ('" . $new_template->getFileName() . "', 
					 " . (is_null($new_template->getScopeId()) ? 'NULL' : $new_template->getScopeId()) . ", '" . 
					 $new_template->getName() . "')";
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
		
		/*
			Updates the given template.
			
			@param $template The template object to update
		*/
		public function updateTemplate($template) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE templates SET name = '" . $template->getName() . "' 
					  , filename = '" . $template->getFileName() . "', scope_id = 
					  '" . $template->getScopeId() . "' WHERE id = " . $template->getId();
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the given template.
			
			@param $template The template to delete
		*/
		public function deleteTemplate($template) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM templates WHERE id = " . $template->getId();
			$mysql_database->executeQuery($query);
			
			// now delete the file
			if ($template->getFileName() != '' && file_exists("../templates/" . $template->getFileName())) {
				$settings = Settings::find();
				unlink($settings->getFrontendTemplateDir() . "/" . $template->getFileName());
			}
		}
	}
?>