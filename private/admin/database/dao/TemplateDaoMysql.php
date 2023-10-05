<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/templates/model/Template.php";
require_once CMS_ROOT . "/modules/templates/model/TemplateVar.php";
require_once CMS_ROOT . "/modules/templates/model/TemplateVarDef.php";
require_once CMS_ROOT . "/modules/templates/model/TemplateFile.php";
require_once CMS_ROOT . "/database/dao/TemplateDao.php";

class TemplateDaoMysql implements TemplateDao {

    private static ?TemplateDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): TemplateDaoMysql {
        if (!self::$instance) {
            self::$instance = new TemplateDaoMysql();
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
        while ($row = $result->fetch_assoc()) {
            $templates[] = Template::constructFromRecord($row);
        }
        return $templates;
    }

    public function createTemplate(): Template {
        $new_template = new Template();
        $new_template->setScopeId(1);
        $new_template->setName("Nieuw template");
        $this->persistTemplate($new_template);
        return $new_template;
    }

    public function persistTemplate(Template $new_template): void {
        $query = "INSERT INTO templates (scope_id, `name`) VALUES (" . (is_null($new_template->getScopeId()) ? 'NULL' : $new_template->getScopeId()) . ", '" .
            $new_template->getName() . "')";
        $this->_mysql_connector->executeQuery($query);
        $new_template->setId($this->_mysql_connector->getInsertId());
    }

    public function updateTemplate(Template $template): void {
        $statement = $this->_mysql_connector->prepareStatement("UPDATE templates SET `name` = ?, template_file_id = ?, scope_id = ? WHERE id = ?");
        $template_id = $template->getId();
        $name = $template->getName();
        $template_file_id = $template->getTemplateFileId();
        $scope_id = $template->getScopeId();
        $statement->bind_param("siii", $name, $template_file_id, $scope_id, $template_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function deleteTemplate(Template $template): void {
        $statement = $this->_mysql_connector->prepareStatement("DELETE FROM templates WHERE id = ?");
        $template_id = $template->getId();
        $statement->bind_param("i", $template_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getTemplatesForTemplateFile(TemplateFile $template_file): array {
        $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM templates WHERE template_file_id = ?");
        $template_file_id = $template_file->getId();
        $statement->bind_param('i', $template_file_id);
        $result = $this->_mysql_connector->executeStatement($statement);

        $templates = array();
        while ($row = $result->fetch_assoc()) {
            $templates[] = Template::constructFromRecord($row);
        }
        return $templates;
    }

    public function getTemplateVars(Template $template): array {
        $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM template_vars WHERE template_id = ?");
        $template_id = $template->getId();
        $statement->bind_param("i", $template_id);
        $result = $this->_mysql_connector->executeStatement($statement);

        $template_vars = array();
        while ($row = $result->fetch_assoc()) {
            $template_vars[] = TemplateVar::constructFromRecord($row);
        }
        return $template_vars;
    }

    public function storeTemplateVar(Template $template, string $name, ?string $value = ""): TemplateVar {
        $new_template_var = new TemplateVar();
        $new_template_var->setName($name);
        $statement = $this->_mysql_connector->prepareStatement("INSERT INTO template_vars (`name`, `value`, template_id) VALUES (?, ?, ?)");
        $template_id = $template->getId();
        $statement->bind_param("ssi", $name, $value, $template_id);
        $this->_mysql_connector->executeStatement($statement);
        $new_template_var->setId($this->_mysql_connector->getInsertId());
        return $new_template_var;
    }

    public function updateTemplateVar(TemplateVar $template_var): void {
        $statement = $this->_mysql_connector->prepareStatement("UPDATE template_vars SET `value` = ? WHERE id = ?");
        $value = $template_var->getValue();
        $id = $template_var->getId();
        $statement->bind_param("si", $value, $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function deleteTemplateVar(TemplateVar $template_var): void {
        $statement = $this->_mysql_connector->prepareStatement("DELETE FROM template_vars WHERE id = ?");
        $id = $template_var->getId();
        $statement->bind_param("i", $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getTemplateFiles(): array {
        $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM template_files");
        $result = $this->_mysql_connector->executeStatement($statement);

        $template_files = array();
        while ($row = $result->fetch_assoc()) {
            $template_files[] = TemplateFile::constructFromRecord($row);
        }
        return $template_files;
    }

    public function getTemplateFile(int $id): ?TemplateFile {
        $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM template_files WHERE id = ?");
        $statement->bind_param("i", $id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return TemplateFile::constructFromRecord($row);
        }
        return null;
    }

    public function storeTemplateFile(TemplateFile $template_file): void {
        $statement = $this->_mysql_connector->prepareStatement("INSERT INTO template_files (`name`) VALUES (?)");
        $name = $template_file->getName();
        $statement->bind_param("s", $name);
        $this->_mysql_connector->executeStatement($statement);
        $template_file->setId($this->_mysql_connector->getInsertId());
    }

    public function deleteTemplateFile(TemplateFile $template_file): void {
        $statement = $this->_mysql_connector->prepareStatement("DELETE FROM template_files WHERE id = ?");
        $id = $template_file->getId();
        $statement->bind_param("i", $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function updateTemplateFile(TemplateFile $template_file): void {
        $statement = $this->_mysql_connector->prepareStatement("UPDATE template_files SET `name` = ?, `filename` = ? WHERE id = ?");
        $id = $template_file->getId();
        $name = $template_file->getName();
        $filename = $template_file->getFileName();
        $statement->bind_param("ssi", $name, $filename, $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getTemplateVarDefs(TemplateFile $template_file): array {
        $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM template_var_defs WHERE template_file_id = ?");
        $template_file_id = $template_file->getId();
        $statement->bind_param("i", $template_file_id);
        $result = $this->_mysql_connector->executeStatement($statement);

        $template_var_defs = array();
        while ($row = $result->fetch_assoc()) {
            $template_var_defs[] = TemplateVarDef::constructFromRecord($row);
        }
        return $template_var_defs;
    }

    public function storeTemplateVarDef(TemplateFile $template_file, string $var_def_name): TemplateVarDef {
        $var_def = new TemplateVarDef();
        $var_def->setName($var_def_name);
        $statement = $this->_mysql_connector->prepareStatement("INSERT INTO template_var_defs (`name`, template_file_id) VALUES (?, ?)");
        $template_file_id = $template_file->getId();
        $statement->bind_param("si", $var_def_name, $template_file_id);
        $this->_mysql_connector->executeStatement($statement);
        $var_def->setId($this->_mysql_connector->getInsertId());
        return $var_def;
    }

    public function updateTemplateVarDef(TemplateVarDef $template_var_def): void {
        $statement = $this->_mysql_connector->prepareStatement("UPDATE template_var_defs SET default_value = ? WHERE id = ?");
        $id = $template_var_def->getId();
        $default_value = $template_var_def->getDefaultValue();
        $statement->bind_param("si", $default_value, $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function deleteTemplateVarDef(TemplateVarDef $template_var_def): void {
        $statement = $this->_mysql_connector->prepareStatement("DELETE FROM template_var_defs WHERE id = ?");
        $id = $template_var_def->getId();
        $statement->bind_param('i', $id);
        $this->_mysql_connector->executeStatement($statement);
    }
}
