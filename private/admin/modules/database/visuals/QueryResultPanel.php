<?php

class QueryResultPanel extends Panel {

    private DatabaseRequestHandler $_request_handler;

    public function __construct(DatabaseRequestHandler $request_handler) {
        parent::__construct('database_query_result_panel_title', 'query_result_panel');
        $this->_request_handler = $request_handler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/query_result_panel.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("fields", $this->_request_handler->getFields());
        $data->assign("values", $this->_request_handler->getValues());
        $data->assign("affected_rows", $this->_request_handler->getAffectedRows());
    }
}
