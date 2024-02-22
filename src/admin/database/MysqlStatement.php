<?php

namespace Obcato\Core\admin\database;

use mysqli_stmt;

class MysqlStatement {

    private mysqli_stmt $statement;

    public function __construct(mysqli_stmt $statement) {
        $this->statement = $statement;
    }

    public function bind_param(string $types, mixed ...$vars): void {
        $this->statement->bind_param($types, ...$vars);
    }

    public
    function execute(): bool {
        return $this->statement->execute();
    }

    public
    function get_result(): MysqlResult|false {
        $result = $this->statement->get_result();
        if (is_bool($result)) {
            return $result;
        } else {
            return new MysqlResult($result);
        }
    }

    public
    function close(): void {
        $this->statement->close();
    }
}