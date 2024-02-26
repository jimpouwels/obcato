<?php

namespace Obcato\Core\database\dao;

interface DatabaseDao {
    public function getTables(): array;

    public function getColumns(string $tableName): array;
}