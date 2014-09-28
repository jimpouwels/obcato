<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "/database/mysql_connector.php";
	include_once CMS_ROOT . "/core/data/template.php";

	class TemplateDao {

		private static $instance;
        private $_mysql_connector;

		private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
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
				$statement = $this->_mysql_connector->prepareStatement("SELECT * FROM templates WHERE id = ?");
                $statement->bind_param("i", $id);
				$result = $this->_mysql_connector->executeStatement($statement);
				while ($row = $result->fetch_assoc())
					$template = Template::constructFromRecord($row);
			}
			
			return $template;
		}

		public function getTemplatesByScope($scope) {
			$templates = array();
			if (!is_null($scope) && $scope != "") {
				$statement = $this->_mysql_connector->prepareStatement("SELECT * FROM templates WHERE scope_id = ?");
                $statement->bind_param("i", $scope->getId());
				$result = $this->_mysql_connector->executeStatement($statement);
				$template = null;
				while ($row = $result->fetch_assoc()) {
					$template = Template::constructFromRecord($row);
					array_push($templates, $template);
				}
			}
			
			return $templates;
		}

		public function getTemplates() {
			$query = "SELECT * FROM templates";
			$result = $this->_mysql_connector->executeQuery($query);
			
			$template = NULL;
			$templates = array();
			while ($row = $result->fetch_assoc()) {
				$template = Template::constructFromRecord($row);
				
				array_push($templates, $template);
			}
			
			return $templates;
		}

		public function createTemplate() {
			$new_template = new Template();
			$new_template->setScopeId(1);
			$new_template->setName("Nieuw template");
			$this->persistTemplate($new_template);
			return $new_template;
		}

		public function getTemplateByFileName($file_name) {
			$query = "SELECT * FROM templates WHERE filename = '" . $file_name . "'";
			$result = $this->_mysql_connector->executeQuery($query);
			$template = NULL;
			while ($row = $result->fetch_assoc()) {
				$template = Template::constructFromRecord($row);
			}
			
			return $template;
		}

		public function persistTemplate($new_template) {
			$query = "INSERT INTO templates (filename, scope_id, name) VALUES ('" . $new_template->getFileName() . "', 
					 " . (is_null($new_template->getScopeId()) ? 'NULL' : $new_template->getScopeId()) . ", '" . 
					 $new_template->getName() . "')";
            $this->_mysql_connector->executeQuery($query);
			$new_template->setId($this->_mysql_connector->getInsertId());
		}

		public function updateTemplate($template) {
			$query = "UPDATE templates SET name = '" . $template->getName() . "' 
					  , filename = '" . $template->getFileName() . "', scope_id = 
					  '" . $template->getScopeId() . "' WHERE id = " . $template->getId();
			$this->_mysql_connector->executeQuery($query);
		}

		public function deleteTemplate($template) {
			$statement = $this->_mysql_connector->prepareStatement("DELETE FROM templates WHERE id = ?");
            $statement->bind_param("i", $template->getId());
            $this->_mysql_connector->executeStatement($statement);
			if ($template->getFileName() != "") {
				$settings = Settings::find();
				unlink($settings->getFrontendTemplateDir() . "/" . $template->getFileName());
			}
		}
	}
?>