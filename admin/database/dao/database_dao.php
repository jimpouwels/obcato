<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "database/mysql_connector.php";
    
    class DatabaseDao {
        
        private static ?DatabaseDao $instance = null;
        private MysqlConnector $_mysql_connector;

        public static function getInstance(): DatabaseDao {
            if (!self::$instance) {
                self::$instance = new DatabaseDao();
            }
            return self::$instance;
        }

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }
        
        public function getTables(): array {
            $query = "SHOW TABLES";
            $result = $this->_mysql_connector->executeQuery($query);
            
            $mysql_database = MysqlConnector::getInstance();
            $database_name = $mysql_database->getDatabaseName();
            $tables = array();
            
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row['Tables_in_' . $database_name];;
            }
            return $tables;
        }
        
        public function getColumns(string $table_name): array {
            $query = 'SHOW columns FROM ' . $table_name;
            $result = $this->_mysql_connector->executeQuery($query);
            
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
    
?>