<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'modules/database/visuals/query_field_panel.php';
    require_once CMS_ROOT . 'modules/database/visuals/query_result_panel.php';

    class QueriesTab extends Visual {

        private static $TABLES_TEMPLATE = "modules/database/queries_tab.tpl";
        private $_pre_handler;

        public function __construct($pre_handler) {
            parent::__construct();
            $this->_pre_handler = $pre_handler;
        }

        public function renderVisual(): string {
            $this->getTemplateEngine()->assign('query_field_panel', $this->renderQueryFieldPanel());
            $this->getTemplateEngine()->assign('query_result_panel', $this->renderQueryResultPanel());
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }

        private function renderQueryFieldPanel() {
            $query_field_panel = new QueryFieldPanel($this->_pre_handler);
            return $query_field_panel->render();
        }

        private function renderQueryResultPanel() {
            $query_result_panel = new QueryResultPanel($this->_pre_handler);
            return $query_result_panel->render();
        }
    }
