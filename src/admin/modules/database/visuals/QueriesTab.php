<?php

namespace Obcato\Core\admin\modules\database\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\modules\database\DatabaseRequestHandler;

class QueriesTab extends Visual {

    private DatabaseRequestHandler $requestHandler;

    public function __construct(TemplateEngine $templateEngine, $requestHandler) {
        parent::__construct($templateEngine);
        $this->requestHandler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return "modules/database/queries_tab.tpl";
    }

    public function load(): void {
        $this->assign('query_field_panel', $this->renderQueryFieldPanel());
        $this->assign('query_result_panel', $this->renderQueryResultPanel());
    }

    private function renderQueryFieldPanel(): string {
        return (new QueryFieldPanel($this->getTemplateEngine(), $this->requestHandler))->render();
    }

    private function renderQueryResultPanel(): string {
        return (new QueryResultPanel($this->getTemplateEngine(), $this->requestHandler))->render();
    }
}
