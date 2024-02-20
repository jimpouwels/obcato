<?php

namespace Obcato\Core\admin\modules\database\visuals;


use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\database\dao\DatabaseDao;
use Obcato\Core\admin\database\dao\DatabaseDaoMysql;

class Tables extends Visual {

    private DatabaseDao $databaseDao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
        $this->databaseDao = DatabaseDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/database/tables.tpl";
    }

    public function load(): void {
        $this->assign('tables', $this->getTables());
    }

    private function getTables(): array {
        $tables = $this->databaseDao->getTables();
        $tablePanels = array();
        foreach ($tables as $table) {
            $tableValue = array();
            $tableValue["name"] = $table;
            $tableValue["columns"] = $this->getColumns($table);
            $tablePanels[] = (new TablePanel($this->getTemplateEngine(), $tableValue))->render();
        }
        return $tablePanels;
    }

    private function getColumns(string $table): array {
        $columnsArray = array();
        foreach ($this->databaseDao->getColumns($table) as $column) {
            $columnValue = array();
            $columnValue["name"] = $column["name"];
            $columnValue["type"] = $column["type"];
            $columnValue["allowed_null"] = $column["allowed_null"] == "YES" ? "Ja" : "Nee";
            $columnsArray[] = $columnValue;
        }
        return $columnsArray;
    }
}