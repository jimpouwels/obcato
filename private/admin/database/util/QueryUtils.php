<?php

class QueryUtils {

    public static function createWhereClause(array $clauses): string {
        return "";
    }
}

abstract class Statement {
    private array $tables = array();
    private array $whereClauses = array();
    private ?OrderBy $orderBy = null;

    public function addWhereByValue(string $table, string $column, WhereType $type, string $match): void {
        $this->addWhere($table, new WhereClauseValue($column, $type, $match));
    }

    public function addWhereByRef(string $table, string $column, string $otherTable, string $otherColumn): void {
        $this->addWhere($table, new WhereClauseRef($column, $otherTable, $otherColumn));
    }

    public function toQuery(): string {
        $query = $this->getBaseString() . $this->formatTables();
        if ($this->whereClauses) {
            $query .= " WHERE ";
        }
        $where = "";
        foreach (array_keys($this->whereClauses) as $whereTable) {
            foreach ($this->whereClauses[$whereTable] as $whereClause) {
                if ($where) {
                    $where .= " AND ";
                }
                $where .= $whereTable . "." . $whereClause->getColumn() . " " . $whereClause->getType()->value . " " . $whereClause->getMatchString();
            }
        }
        $query .= $where;
        if ($this->orderBy) {
            $query .= " " . $this->orderBy->toString();
        }
        return $query;
    }

    public function orderBy(string $table, $column): void {
        $this->orderBy = new OrderBy($table, $column);
    }

    public function getBindString(): string {
        return str_repeat("s", count($this->whereClauses));
    }

    public function getMatches(): array {
        $matches = array();
        foreach (array_keys($this->whereClauses) as $table) {
            foreach ($this->whereClauses[$table] as $whereClause) {
                if ($whereClause->getMatch()) {
                    $match = $whereClause->getMatch();
                    if ($whereClause->getType() == WhereType::Like) {
                        $match = "%$match%";
                    }
                    $matches[] = $match;
                }
            }
        }
        return $matches;
    }

    protected abstract function getBaseString(): string;


    protected function addTable(string $table): void {
        $this->tables[] = $table;
    }

    private function addWhere(string $table, WhereClause $whereClauseRef): void {
        if (!isset($this->whereClauses[$table])) {
            $this->whereClauses[$table] = array();
        }
        $this->whereClauses[$table][] = $whereClauseRef;
    }

    private function formatTables(): string {
        $from = "";
        foreach ($this->tables as $table) {
            $from .= $table . " " . $table . ", ";
        }
        return rtrim($from, ", ");
    }
}

class SelectStatement extends Statement {
    private array $selectFields = array();
    private bool $distinct;

    public function __construct(bool $distinct = false) {
        $this->distinct = $distinct;
    }

    public function addFrom(string $table, array $fields): void {
        $this->addTable($table);
        if (!isset($this->selectFields[$table])) {
            $this->selectFields[$table] = array();
        }
        $this->selectFields[$table] = array_merge($this->selectFields[$table], $fields);
    }

    public function getSelectFields(): array {
        return $this->selectFields;
    }

    public function prepare(mysqli_stmt $sqlStatement) {}

    public function execute(MysqlConnector $mysqlConnector): bool|mysqli_result {
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

class OrderBy {
    private string $table;
    private string $column;

    public function __construct(string $table, string $column) {
        $this->table = $table;
        $this->column = $column;
    }

    public function toString(): string {
        return "ORDER BY " . $this->table . "." . $this->column;
    }
}

abstract class WhereClause {

    private string $column;
    private WhereType $type;

    public function __construct(string $column, WhereType $type) {
        $this->column = $column;
        $this->type = $type;
    }

    public function getColumn(): string {
        return $this->column;
    }

    public function getType(): WhereType {
        return $this->type;
    }

    public abstract function getMatch(): ?string;

    public abstract function getMatchString(): string;
}

class WhereClauseValue extends WhereClause {

    private string $match;

    public function __construct(string $column, WhereType $type, string $match) {
        parent::__construct($column, $type);
        $this->match = $match;
    }

    public function getMatch(): ?string {
        return $this->match;
    }

    public function getMatchString(): string {
        return "?";
    }
}

class WhereClauseRef extends WhereClause {

    private string $otherTable;
    private string $otherColumn;

    public function __construct(string $column, string $otherTable, string $otherColumn) {
        parent::__construct($column, WhereType::Equals);
        $this->otherTable = $otherTable;
        $this->otherColumn = $otherColumn;
    }

    public function getMatch(): ?string {
        return null;
    }

    public function getMatchString(): string {
        return $this->otherTable . "." . $this->otherColumn;
    }
}

enum WhereType: string {
    case Equals = "=";
    case Like = "LIKE";
}