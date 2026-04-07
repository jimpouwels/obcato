<?php

namespace Obcato\Core\modules\templates\visuals\template_editor;

use Obcato\Core\database\dao\ScopeDao;
use Obcato\Core\database\dao\ScopeDaoMysql;
use Obcato\Core\modules\templates\model\Scope;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class ScopeSelector extends Panel {

    private ScopeDao $scopeDao;
    private ?Scope $currentScope;

    public function __construct(?Scope $currentScope = null) {
        parent::__construct('templates_scope_list_title', 'scope_selector_panel');
        $this->scopeDao = ScopeDaoMysql::getInstance();
        $this->currentScope = $currentScope;
    }

    public function getPanelContentTemplate(): string {
        return "templates/templates/scope_selector.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("scopes", $this->getAllScopes());
    }

    private function getAllScopes(): array {
        $scopes = array();
        foreach ($this->scopeDao->getScopes() as $scope) {
            $scopeArray = array();
            $scopeArray["label"] = $this->getTextResource($scope->getIdentifier() . "_scope_label");
            $scopeArray["identifier"] = $scope->getIdentifier();
            $scopeArray["is_active"] = $this->currentScope && $this->currentScope->getIdentifier() === $scope->getIdentifier();
            $scopes[] = $scopeArray;
        }
        return $scopes;
    }

}
