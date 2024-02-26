<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\database\MysqlConnector;

class DatabaseDaoMysql implements DatabaseDao {

    private static ?DatabaseDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): DatabaseDaoMysql {
        if (!self::$instance) {
            self::$instance = new DatabaseDaoMysql();
        }
        return self::$instance;
    }

    public function getTables(): array {
        $query = "SHOW TABLES";
        $result = $this->mysqlConnector->executeQuery($query);

        $tables = array();

        while ($row = $result->fetch_assoc()) {
            $tables[] = $row['Tables_in_' . $this->mysqlConnector->getDatabaseName()];
        }
        return $tables;
    }

    public function getColumns(string $tableName): array {
        $query = 'SHOW columns FROM ' . $tableName;
        $result = $this->mysqlConnector->executeQuery($query);

        $columns = array();
        while ($row = $result->fetch_assoc()) {
            $column = array();
            $column['name'] = $row['Field'];
            $column['type'] = $row['Type'];
            $column['allowed_null'] = $row['Null'];

            $columns[] = $column;
        }

        return $columns;
    }

}
