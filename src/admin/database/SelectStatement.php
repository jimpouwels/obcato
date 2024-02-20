<?php

namespace Obcato\Core\admin\database;

use Obcato\ComponentApi\MysqlResult;
use Obcato\ComponentApi\MysqlStatement;

class SelectStatement extends Statement {
    private array $selectFields = array();
    private bool $distinct;

    public function __construct(bool $distinct = false) {
        $this->distinct = $distinct;
    }

    public function from(string $table, array $fields): void {
        $this->addTable($table);
        if (!isset($this->selectFields[$table])) {
            $this->selectFields[$table] = array();
        }
        $this->selectFields[$table] = array_merge($this->selectFields[$table], $fields);
    }

    public function getSelectFields(): array {
        return $this->selectFields;
    }

    public function prepare(MysqlStatement $sqlStatement) {}

    public function execute(MysqlConnector $mysqlConnector): bool|MysqlResult {
        $sqlStatement = $mysqlConnector->prepareStatement($this->toQuery());
        if ($this->getBindString()) {
            $sqlStatement->bind_param($this->getBindString(), ...$this->getMatches());
        }
        return $mysqlConnector->executeStatement($sqlStatement);
    }

    protected function getBaseString(): string {
        $selectString = "";
        foreach (array_keys($this->selectFields) as $fromTable) {
            foreach ($this->selectFields[$fromTable] as $fromField) {
                $selectString .= $fromTable . "." . $fromField . ", ";
            }
        }
        return "SELECT " . ($this->distinct ? "DISTINCT " : "") . rtrim($selectString, ", ") . " FROM ";
    }
}