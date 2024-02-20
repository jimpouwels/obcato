<?php

namespace Obcato\Core\admin\modules\templates\model;

use Obcato\Core\admin\core\model\Entity;
use Obcato\Core\admin\database\dao\TemplateDaoMysql;

class Template extends Entity {

    private string $name;
    private int $scopeId;
    private array $templateVars = array();
    private ?int $templateFileId = null;

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
        $this->setTemplateVars(TemplateDaoMysql::getInstance()->getTemplateVars($this));
    }

    public function getTemplateVars(): array {
        return $this->templateVars;
    }

    public function setTemplateVars(array $templateVars): void {
        $this->templateVars = $templateVars;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getTemplateFileId(): ?int {
        return $this->templateFileId;
    }

    public function setTemplateFileId(?int $templateFileId): void {
        $this->templateFileId = $templateFileId;
    }

    public function getScope(): Scope {
        $dao = ScopeDaoMysql::getInstance();
        return $dao->getScope($this->scopeId);
    }

    public function getScopeId(): int {
        return $this->scopeId;
    }

    public function setScopeId(int $scopeId): void {
        $this->scopeId = $scopeId;
    }

    public function addTemplateVar(TemplateVar $templateVar): void {
        $this->templateVars[] = $templateVar;
    }

    public function deleteTemplateVar(TemplateVar $templateVarToDelete): void {
        $this->templateVars = array_filter($this->templateVars, function ($templateVar) use ($templateVarToDelete) {
            return $templateVar->getId() !== $templateVarToDelete->getId();
        });
    }

}