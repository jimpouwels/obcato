<?php
    defined('_ACCESS') or die;

    class QueryFieldPanel extends Panel {

        private static $TABLES_TEMPLATE = "modules/database/query_field_panel.tpl";
        private $_pre_handler;

        public function __construct($pre_handler) {
            parent::__construct('Query editor', 'queries_form_wrapper');
            $this->_pre_handler = $pre_handler;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("query_field", $this->renderQueryField());
            $this->getTemplateEngine()->assign("execute_query_button", $this->renderExecuteButton());
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }

        private function renderQueryField() {
            $query_field = new TextArea('query', "Query", $this->_pre_handler->getQuery(), true, false, "");
            return $query_field->render();
        }

        private function renderExecuteButton() {
            $execute_button = new Button("", "Query uitvoeren", "document.getElementById('query_execute_form').submit(); return false;");
            return $execute_button->render();
        }
    }
