<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/DatabaseDaoMysql.php";

class TablePanel extends Panel {

    private array $_table;

    public function __construct(array $table) {
        parent::__construct($table['name'], 'table_details_panel');
        $this->_table = $table;
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/table.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("table", $this->_table);
    }
}
