<?php
    defined('_ACCESS') or die;

    class QueryFieldPanel extends Panel {

        private static $TABLES_TEMPLATE = "modules/database/query_field_panel.tpl";
        private $_template_engine;
        private $_pre_handler;

        public function __construct($pre_handler) {
            parent::__construct('Query editor');
            $this->_pre_handler = $pre_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("query_field", $this->renderQueryField());
            $this->_template_engine->assign("execute_query_button", $this->renderExecuteButton());
            return $this->_template_engine->fetch(self::$TABLES_TEMPLATE);
        }

        private function renderQueryField() {
            $query_field = new TextArea('query', "Query", $this->_pre_handler->getQuery(), 55, 10, true, false, "");
            return $query_field->render();
        }

        private function renderExecuteButton() {
            $execute_button = new Button("", "Query uitvoeren", "document.getElementById('query_execute_form').submit(); return false;");
            return $execute_button->render();
        }
    }
