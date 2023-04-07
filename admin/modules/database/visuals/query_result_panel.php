<?php
    defined('_ACCESS') or die;

    class QueryResultPanel extends Panel {

        private static $TABLES_TEMPLATE = "modules/database/query_result_panel.tpl";
        private $_template_engine;
        private $_pre_handler;

        public function __construct($pre_handler) {
            parent::__construct('Resultaten', 'query_result_panel');
            $this->_pre_handler = $pre_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("fields", $this->_pre_handler->getFields());
            $this->_template_engine->assign("values", $this->_pre_handler->getValues());
            $this->_template_engine->assign("affected_rows", $this->_pre_handler->getAffectedRows());
            return $this->_template_engine->fetch(self::$TABLES_TEMPLATE);
        }
    }
