<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/DatabaseDaoMysql.php";

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
