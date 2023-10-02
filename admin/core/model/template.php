<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/entity.php";
require_once CMS_ROOT . "/database/dao/scope_dao.php";
require_once CMS_ROOT . "/database/dao/template_dao.php";

class Template extends Entity {

    private string $_name;
    private int $_scope_id;
    private array $_template_vars = array();
    private ?int $_template_file_id = null;

    public static function constructFromRecord(array $row): Template {
        $template = new Template();
        $template->initFromDb($row);
        return $template;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setScopeId($row['scope_id']);
        $this->setTemplateFileId($row['template_file_id']);
        parent::initFromDb($row);
        $this->setTemplateVars(TemplateDao::getInstance()->getTemplateVars($this));
    }

    public function getTemplateVars(): array {
        return $this->_template_vars;
    }

    public function setTemplateVars(array $template_vars): void {
        $this->_template_vars = $template_vars;
    }

    public function getName(): string {
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

    public function getTemplateFileId(): ?int {
        return $this->_template_file_id;
    }

    public function setTemplateFileId(?int $template_file_id): void {
        $this->_template_file_id = $template_file_id;
    }

    public function getScope(): Scope {
        $dao = ScopeDao::getInstance();
        return $dao->getScope($this->_scope_id);
    }

    public function getScopeId(): int {
        return $this->_scope_id;
    }

    public function setScopeId(int $scope_id): void {
        $this->_scope_id = $scope_id;
    }

    public function addTemplateVar(TemplateVar $template_var): void {
        $this->_template_vars[] = $template_var;
    }

    public function deleteTemplateVar(TemplateVar $template_var_to_delete): void {
        $this->_template_vars = array_filter($this->_template_vars, function ($template_var) use ($template_var_to_delete) {
            return $template_var->getId() !== $template_var_to_delete->getId();
        });
    }

}