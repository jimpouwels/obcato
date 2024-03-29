<?php

namespace Obcato\Core\modules\database\visuals;

use Obcato\Core\modules\database\DatabaseRequestHandler;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class QueryResultPanel extends Panel {

    private DatabaseRequestHandler $requestHandler;

    public function __construct(DatabaseRequestHandler $requestHandler) {
        parent::__construct('database_query_result_panel_title', 'query_result_panel');
        $this->requestHandler = $requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/query_result_panel.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("fields", $this->requestHandler->getFields());
        $data->assign("values", $this->requestHandler->getValues());
        $data->assign("affected_rows", $this->requestHandler->getAffectedRows());
    }
}
