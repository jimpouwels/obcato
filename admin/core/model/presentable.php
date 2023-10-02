<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/entity.php";

abstract class Presentable extends Entity {

    private ?int $_template_id = null;
    private int $_scope_id;

    public function __construct(int $scope_id) {
        $this->_scope_id = $scope_id;
    }

    public function getTemplate(): ?Template {
        $dao = TemplateDao::getInstance();
        if ($this->_template_id) {
            return $dao->getTemplate($this->_template_id);
        } else {
            return null;
        }
    }

    public function setTemplate(Template $template): void {
        if (!is_null($template)) {
            $this->_template_id = $template->getId();
        }
    }

    public function getTemplateId(): ?int {
        return $this->_template_id;
    }

    public function setTemplateId(?int $template_id): void {
        $this->_template_id = $template_id;
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

    protected function initFromDb(array $row): void {
        $this->setTemplateId($row['template_id']);
        $this->setScopeId($row['scope_id']);
        parent::initFromDb($row);
    }
}