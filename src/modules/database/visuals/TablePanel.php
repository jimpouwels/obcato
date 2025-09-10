<?php

namespace Obcato\Core\modules\database\visuals;

use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class TablePanel extends Panel {

    private array $table;

    public function __construct(array $table) {
        parent::__construct($table['name'], 'table_details_panel');
        $this->table = $table;
    }

    public function getPanelContentTemplate(): string {
        return "database/templates/table.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("table", $this->table);
    }
}