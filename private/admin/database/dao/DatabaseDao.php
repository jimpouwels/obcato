<?php

interface DatabaseDao {
    public function getTables(): array;

    public function getColumns(string $table_name): array;
}