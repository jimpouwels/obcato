<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class ScopeSelector extends Panel {

        private static string $SCOPE_SELECTOR = "templates/scope_selector.tpl";
        private TemplateDao $_template_dao;
        private ScopeDao $_scope_dao;

        public function __construct() {
            parent::__construct('Componenten', 'scope_selector_fieldset');
            $this->_template_dao = TemplateDao::getInstance();
            $this->_scope_dao = ScopeDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("scopes", $this->getAllScopes());
            return $this->getTemplateEngine()->fetch("modules/" . self::$SCOPE_SELECTOR);
        }

        private function getAllScopes(): array {
            $scopes = array();
            foreach ($this->_scope_dao->getScopes() as $scope) {
                $scopes[] = $scope->getName();
            }
            return $scopes;
        }

    }
