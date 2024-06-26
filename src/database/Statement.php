<?php

namespace Obcato\Core\database;

abstract class Statement {
    private array $tables = array();
    private array $whereClauses = array();
    private array $fixedWhereClauses = array();
    private ?OrderBy $orderBy = null;
    private ?Join $join = null;

    public function where(string $table, string $column, WhereType $type, string|int $match, Prepared $prepared = Prepared::Yes): void {
        if (!isset($this->whereClauses[$table])) {
            $this->whereClauses[$table] = array();
        }
        $this->whereClauses[$table][] = (new WhereClause($column, $type, $match, $prepared));
    }

    public function innerJoin(string $tableLeft, string $columnLeft, string $tableRight, string $columnRight): void {
        $this->join = new Join(JoinType::Inner, $tableLeft, $columnLeft, $tableRight, $columnRight);
    }

    public function toQuery(): string {
        $query = $this->getBaseString() . $this->formatTables();
        if ($this->join) {
            $query .= " " . $this->join->toString();
        }
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
        foreach (array_keys($this->fixedWhereClauses) as $whereTable) {
            foreach ($this->fixedWhereClauses[$whereTable] as $whereClause) {
                if ($where) {
                    $where .= " AND ";
                }
                $where .= $whereTable . "." . $whereClause->getColumn() . " " . $whereClause->getType()->value . " " . $whereClause->getMatch();
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
        $bindString = "";
        foreach (array_keys($this->whereClauses) as $table) {
            foreach ($this->whereClauses[$table] as $whereClause) {
                $bindString .= $whereClause->getBindString();
            }
        }
        return $bindString;
    }

    public function getMatches(): array {
        $matches = array();
        foreach (array_keys($this->whereClauses) as $table) {
            foreach ($this->whereClauses[$table] as $whereClause) {
                if ($whereClause->getMatch() && $whereClause->isPrepared()) {
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

    private function formatTables(): string {
        $from = "";
        foreach ($this->tables as $table) {
            if (!$this->join || $this->join->getTableRight() != $table) {
                $from .= $table . " " . $table . ", ";
            }
        }
        return rtrim($from, ", ");
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

class Join {
    private JoinType $joinType;
    private string $tableLeft;
    private string $columnLeft;
    private string $tableRight;
    private string $columnRight;

    public function __construct(JoinType $joinType, string $tableLeft, string $columnLeft, string $tableRight, string $columnRight) {
        $this->joinType = $joinType;
        $this->tableLeft = $tableLeft;
        $this->columnLeft = $columnLeft;
        $this->tableRight = $tableRight;
        $this->columnRight = $columnRight;
    }

    public function toString(): string {
        return $this->joinType->value . " JOIN " . $this->tableRight . " ON " . $this->tableLeft . "." . $this->columnLeft . " = " . $this->tableRight . "." . $this->columnRight;
    }

    public function getTableRight(): string {
        return $this->tableRight;
    }
}

enum JoinType: string {
    case Inner = "INNER";
}

class WhereClause {

    private string $column;
    private string|int $match;
    private WhereType $type;
    private Prepared $prepared;

    public function __construct(string $column, WhereType $type, string|int $match, Prepared $prepared) {
        $this->column = $column;
        $this->match = $match;
        $this->type = $type;
        $this->prepared = $prepared;
    }

    public function getColumn(): string {
        return $this->column;
    }

    public function getType(): WhereType {
        return $this->type;
    }

    public function getMatch(): ?string {
        return $this->match;
    }

    public function getBindString(): string {
        if ($this->isPrepared()) {
            return (is_int($this->match)) ? "i" : "s";
        }
        return "";
    }

    public function isPrepared(): bool {
        return $this->prepared == Prepared::Yes;
    }

    public function getMatchString(): string {
        return $this->prepared == Prepared::Yes ? "?" : $this->getMatch();
    }

}

enum WhereType: string {
    case Equals = "=";
    case Like = "LIKE";
    case LowerThan = "<=";
}

enum Prepared: string {
    case Yes = "true";
    case No = "false";
}