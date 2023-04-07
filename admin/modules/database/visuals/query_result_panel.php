<?php
    defined('_ACCESS') or die;

    class QueryResultPanel extends Panel {

        private static $TABLES_TEMPLATE = "modules/database/query_result_panel.tpl";
        private $_pre_handler;

        public function __construct($pre_handler) {
            parent::__construct('Resultaten', 'query_result_panel');
            $this->_pre_handler = $pre_handler;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("fields", $this->_pre_handler->getFields());
            $this->getTemplateEngine()->assign("values", $this->_pre_handler->getValues());
            $this->getTemplateEngine()->assign("affected_rows", $this->_pre_handler->getAffectedRows());
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }
    }
