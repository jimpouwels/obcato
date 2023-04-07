<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class ScopeSelector extends Panel {

        private static $SCOPE_SELECTOR = "templates/scope_selector.tpl";

        private $_template_dao;
        private $_scope_dao;

        public function __construct() {
            parent::__construct('Componenten', 'scope_selector_fieldset');
            $this->_template_dao = TemplateDao::getInstance();
            $this->_scope_dao = ScopeDao::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("scopes", $this->getAllScopes());
            return $this->getTemplateEngine()->fetch("modules/" . self::$SCOPE_SELECTOR);
        }

        private function getAllScopes() {
            $scopes = array();
            foreach ($this->_scope_dao->getScopes() as $scope) {
                $scopes[] = $scope->getName();
            }
            return $scopes;
        }

    }
