<?php
defined('_ACCESS') or die;

class QueryFieldPanel extends Panel {

    private DatabaseRequestHandler $_request_handler;

    public function __construct($request_handler) {
        parent::__construct('database_query_editor_title', 'queries_form_wrapper');
        $this->_request_handler = $request_handler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/query_field_panel.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("query_field", $this->renderQueryField());
        $data->assign("execute_query_button", $this->renderExecuteButton());
    }

    private function renderQueryField(): string {
        $query_field = new TextArea('query', "database_query_query_field_label", $this->_request_handler->getQuery(), true, false, "");
        return $query_field->render();
    }

    private function renderExecuteButton(): string {
        $execute_button = new Button("", "database_query_execute_button_label", "document.getElementById('query_execute_form').submit(); return false;");
        return $execute_button->render();
    }
}
