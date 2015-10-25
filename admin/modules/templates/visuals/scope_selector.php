<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class ScopeSelector extends Panel {

        private static $SCOPE_SELECTOR = "templates/scope_selector.tpl";

        private $_template_dao;
        private $_scope_dao;
        private $_template_engine;

        public function __construct() {
            parent::__construct('Presenteerbare componenten', 'scope_selector_fieldset');
            $this->_template_dao = TemplateDao::getInstance();
            $this->_scope_dao = ScopeDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("scopes", $this->getAllScopes());
            return $this->_template_engine->fetch("modules/" . self::$SCOPE_SELECTOR);
        }

        private function getAllScopes() {
            $scopes = array();
            foreach ($this->_scope_dao->getScopes() as $scope) {
                $scope_data = array();
                $scopes[] = $scope->getName();
            }
            return $scopes;
        }

    }
