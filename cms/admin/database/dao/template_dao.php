<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "database/mysql_connector.php";
	include_once CMS_ROOT . "core/data/template.php";

	class TemplateDao {

		private static $instance;

		private function __construct() {
		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new TemplateDao();
			}
			return self::$instance;
		}
		
		public function getTemplate($id) {
			$template = null;
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

		public function getTemplatesByScope($scope) {
			$templates = array();
			if (!is_null($scope) && $scope != '') {
				$mysql_database = MysqlConnector::getInstance(); 
			
				$query = "SELECT * FROM templates WHERE scope_id = '" . $scope->getId() . "'";
				$result = $mysql_database->executeSelectQuery($query);
				$template = null;
				while ($row = mysql_fetch_assoc($result)) {
					$template = Template::constructFromRecord($row);
					
					array_push($templates, $template);
				}
			}
			
			return $templates;
		}

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

		public function createTemplate() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_template = new Template();
			$new_template->setScopeId(1);
			$new_template->setName("Nieuw template");
			$this->persistTemplate($new_template);
			
			return $new_template;
		}

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

		public function persistTemplate($new_template) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO templates (filename, scope_id, name) VALUES ('" . $new_template->getFileName() . "', 
					 " . (is_null($new_template->getScopeId()) ? 'NULL' : $new_template->getScopeId()) . ", '" . 
					 $new_template->getName() . "')";
			$mysql_database->executeQuery($query);
			$new_template->setId(mysql_insert_id());
		}

		public function updateTemplate($template) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE templates SET name = '" . $template->getName() . "' 
					  , filename = '" . $template->getFileName() . "', scope_id = 
					  '" . $template->getScopeId() . "' WHERE id = " . $template->getId();
			$mysql_database->executeQuery($query);
		}

		public function deleteTemplate($template) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM templates WHERE id = " . $template->getId();
			$mysql_database->executeQuery($query);

			if ($template->getFileName() != "") {
				$settings = Settings::find();
				unlink($settings->getFrontendTemplateDir() . "/" . $template->getFileName());
			}
		}
	}
?>