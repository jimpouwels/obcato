<?php
require_once CMS_ROOT . '/modules/database/visuals/QueryFieldPanel.php';
require_once CMS_ROOT . '/modules/database/visuals/QueryResultPanel.php';

class QueriesTab extends Visual {

    private DatabaseRequestHandler $_request_handler;

    public function __construct($requestHandler) {
        parent::__construct();
        $this->_request_handler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return "modules/database/queries_tab.tpl";
    }

    public function load(): void {
        $this->assign('query_field_panel', $this->renderQueryFieldPanel());
        $this->assign('query_result_panel', $this->renderQueryResultPanel());
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
