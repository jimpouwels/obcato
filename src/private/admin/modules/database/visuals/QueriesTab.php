<?php
require_once CMS_ROOT . '/modules/database/visuals/QueryFieldPanel.php';
require_once CMS_ROOT . '/modules/database/visuals/QueryResultPanel.php';

class QueriesTab extends Obcato\ComponentApi\Visual {

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