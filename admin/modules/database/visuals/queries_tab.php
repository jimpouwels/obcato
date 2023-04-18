<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'modules/database/visuals/query_field_panel.php';
    require_once CMS_ROOT . 'modules/database/visuals/query_result_panel.php';

    class QueriesTab extends Visual {

        private static string $TABLES_TEMPLATE = "modules/database/queries_tab.tpl";
        private DatabaseRequestHandler $_request_handler;

        public function __construct($request_handler) {
            parent::__construct();
            $this->_request_handler = $request_handler;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign('query_field_panel', $this->renderQueryFieldPanel());
            $this->getTemplateEngine()->assign('query_result_panel', $this->renderQueryResultPanel());
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }

        private function renderQueryFieldPanel(): string {
            $query_field_panel = new QueryFieldPanel($this->_request_handler);
            return $query_field_panel->render();
        }

        private function renderQueryResultPanel(): string {
            $query_result_panel = new QueryResultPanel($this->_request_handler);
            return $query_result_panel->render();
        }
    }
