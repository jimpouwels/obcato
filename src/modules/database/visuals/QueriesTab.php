<?php

namespace Obcato\Core\modules\database\visuals;

use Obcato\Core\modules\database\DatabaseRequestHandler;
use Obcato\Core\view\views\Visual;

class QueriesTab extends Visual {

    private DatabaseRequestHandler $requestHandler;

    public function __construct($requestHandler) {
        parent::__construct();
        $this->requestHandler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return "database/templates/queries_tab.tpl";
    }

    public function load(): void {
        $this->assign('query_field_panel', $this->renderQueryFieldPanel());
        $this->assign('query_result_panel', $this->renderQueryResultPanel());
    }

    private function renderQueryFieldPanel(): string {
        return (new QueryFieldPanel($this->requestHandler))->render();
    }

    private function renderQueryResultPanel(): string {
        return (new QueryResultPanel($this->requestHandler))->render();
    }
}
