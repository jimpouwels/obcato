<?php

namespace Obcato\Core\admin\modules\database\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\view\views\Panel;

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