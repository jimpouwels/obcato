<?php

namespace Obcato\Core\database;

use mysqli_result;

class MysqlResult {

    private mysqli_result $result;

    public function __construct(mysqli_result $result) {
        $this->result = $result;
    }

    public function fetch_assoc(): array|false|null {
        return $this->result->fetch_assoc();
    }

    public function fetch_fields(): array {
        return $this->result->fetch_fields();
    }
}