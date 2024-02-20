<?php

namespace Obcato\Core\admin\database\dao;

interface DatabaseDao {
    public function getTables(): array;

    public function getColumns(string $tableName): array;
}