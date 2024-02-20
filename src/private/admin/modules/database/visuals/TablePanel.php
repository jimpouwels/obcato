<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TablePanel extends Panel {

    private array $table;

    public function __construct(TemplateEngine $templateEngine, array $table) {
        parent::__construct($templateEngine, $table['name'], 'table_details_panel');
        $this->table = $table;
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/table.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("table", $this->table);
    }
}
