<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class ScopeSelector extends Panel {

        private TemplateDao $_template_dao;
        private ScopeDao $_scope_dao;

        public function __construct() {
            parent::__construct('Componenten', 'scope_selector_fieldset');
            $this->_template_dao = TemplateDao::getInstance();
            $this->_scope_dao = ScopeDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/templates/scope_selector.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("scopes", $this->getAllScopes());
        }

        private function getAllScopes(): array {
            $scopes = array();
            foreach ($this->_scope_dao->getScopes() as $scope) {
                $scope_array = array();
                $scope_array["label"] = $this->getTextResource($scope->getIdentifier() . "_scope_label");
                $scope_array["identifier"] = $scope->getIdentifier();
                $scopes[] = $scope_array;
            }
            return $scopes;
        }

    }
