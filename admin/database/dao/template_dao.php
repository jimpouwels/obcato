<?php

    
    defined('_ACCESS') or die;

    include_once CMS_ROOT . "database/mysql_connector.php";
    include_once CMS_ROOT . "core/model/template.php";

    class TemplateDao {

        private static ?TemplateDao $instance = null;
        private MysqlConnector $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance(): TemplateDao {
            if (!self::$instance) {
                self::$instance = new TemplateDao();
            }
            return self::$instance;
        }
        
        public function getTemplate(int $id): ?Template {
            if (!is_null($id)) {
                $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM templates WHERE id = ?");
                $statement->bind_param("i", $id);
                $result = $this->_mysql_connector->executeStatement($statement);
                while ($row = $result->fetch_assoc()) {
                    return Template::constructFromRecord($row);
                }
            }
            return null;
        }

        public function getTemplatesByScope(Scope $scope): array {
            $templates = array();
            if (!is_null($scope) && $scope != "") {
                $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM templates WHERE scope_id = ?");
                $scope_id = $scope->getId();
                $statement->bind_param("i", $scope_id);
                $result = $this->_mysql_connector->executeStatement($statement);
                while ($row = $result->fetch_assoc()) {
                    $templates[] = Template::constructFromRecord($row);
                }
            }
            
            return $templates;
        }

        public function getTemplates(): array {
            $query = "SELECT * FROM templates";
            $result = $this->_mysql_connector->executeQuery($query);
            $templates = array();
            while ($row = $result->fetch_assoc())
                $templates[] = Template::constructFromRecord($row);
            return $templates;
        }

        public function createTemplate(): Template {
            $new_template = new Template();
            $new_template->setScopeId(1);
            $new_template->setName("Nieuw template");
            $this->persistTemplate($new_template);
            return $new_template;
        }

        public function getTemplateByFileName($file_name) {
            $query = "SELECT * FROM templates WHERE filename = '" . $file_name . "'";
            $result = $this->_mysql_connector->executeQuery($query);
            while ($row = $result->fetch_assoc())
                return Template::constructFromRecord($row);
        }

        public function persistTemplate(Template $new_template): void {
            $query = "INSERT INTO templates (filename, scope_id, name) VALUES ('" . $new_template->getFileName() . "', 
                     " . (is_null($new_template->getScopeId()) ? 'NULL' : $new_template->getScopeId()) . ", '" . 
                     $new_template->getName() . "')";
            $this->_mysql_connector->executeQuery($query);
            $new_template->setId($this->_mysql_connector->getInsertId());
        }

        public function updateTemplate(Template $template): void {
            $query = "UPDATE templates SET name = '" . $template->getName() . "' 
                      , filename = '" . $template->getFileName() . "', scope_id = 
                      '" . $template->getScopeId() . "' WHERE id = " . $template->getId();
            $this->_mysql_connector->executeQuery($query);
        }

        public function deleteTemplate(Template $template): void {
            $statement = $this->_mysql_connector->prepareStatement("DELETE FROM templates WHERE id = ?");
            $temlate_id = $template->getId();
            $statement->bind_param("i", $temlate_id);
            $this->_mysql_connector->executeStatement($statement);
            if ($template->getFileName() != "") {
                $file_name = FRONTEND_TEMPLATE_DIR . "/" . $template->getFileName();
                if (file_exists($file_name)) {
                    unlink($file_name);
                }
            }
        }
    }
?>