<?php

class QueryFieldPanel extends Panel {

    private DatabaseRequestHandler $requestHandler;

    public function __construct($requestHandler) {
        parent::__construct('database_query_editor_title', 'queries_form_wrapper');
        $this->requestHandler = $requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/query_field_panel.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("query_field", $this->renderQueryField());
        $data->assign("execute_query_button", $this->renderExecuteButton());
    }

    private function renderQueryField(): string {
        $queryField = new TextArea('query', "database_query_query_field_label", $this->requestHandler->getQuery(), true, false, "");
        return $queryField->render();
    }

    private function renderExecuteButton(): string {
        $executeButton = new Button("", "database_query_execute_button_label", "document.getElementById('query_execute_form').submit(); return false;");
        return $executeButton->render();
    }
}
