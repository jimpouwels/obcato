<?php

namespace Obcato\Core\modules\templates\model;

use Obcato\Core\core\model\Entity;
use Obcato\Core\database\dao\ScopeDaoMysql;
use Obcato\Core\database\dao\TemplateDaoMysql;

abstract class Presentable extends Entity {

    private ?int $templateId = null;
    private int $scopeId;

    public function __construct(int $scopeId) {
        $this->scopeId = $scopeId;
    }

    public function getTemplate(): ?Template {
        $dao = TemplateDaoMysql::getInstance();
        if ($this->templateId) {
            return $dao->getTemplate($this->templateId);
        } else {
            return null;
        }
    }

    public function setTemplate(Template $template): void {
        $this->templateId = $template->getId();
    }

    public function getTemplateId(): ?int {
        return $this->templateId;
    }

    public function setTemplateId(?int $templateId): void {
        $this->templateId = $templateId;
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

    protected function initFromDb(array $row): void {
        $this->setTemplateId($row['template_id']);
        $this->setScopeId($row['scope_id']);
        parent::initFromDb($row);
    }
}