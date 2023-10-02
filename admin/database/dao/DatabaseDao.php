<?php
defined('_ACCESS') or die;

interface DatabaseDao {
    public function getTables(): array;

    public function getColumns(string $table_name): array;
}