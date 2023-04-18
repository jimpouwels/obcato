<?php
    defined('_ACCESS') or die;

    class QueryResultPanel extends Panel {

        private static string $TABLES_TEMPLATE = "modules/database/query_result_panel.tpl";
        private DatabaseRequestHandler $_request_handler;

        public function __construct(DatabaseRequestHandler $request_handler) {
            parent::__construct('Resultaten', 'query_result_panel');
            $this->_request_handler = $request_handler;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("fields", $this->_request_handler->getFields());
            $this->getTemplateEngine()->assign("values", $this->_request_handler->getValues());
            $this->getTemplateEngine()->assign("affected_rows", $this->_request_handler->getAffectedRows());
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }
    }
