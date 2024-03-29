<?php

namespace Obcato\Core\modules\templates\visuals\template_editor;

use Obcato\Core\database\dao\ScopeDao;
use Obcato\Core\database\dao\ScopeDaoMysql;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class ScopeSelector extends Panel {

    private ScopeDao $scopeDao;

    public function __construct() {
        parent::__construct('templates_scope_list_title', 'scope_selector_panel');
        $this->scopeDao = ScopeDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/scope_selector.tpl";
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
            $scopes[] = $scopeArray;
        }
        return $scopes;
    }

}
