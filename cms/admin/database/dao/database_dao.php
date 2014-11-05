<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "database/mysql_connector.php";
    
    class DatabaseDao {
        
        private static $instance;

        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new DatabaseDao();
            }
            return self::$instance;
        }
        
        public function getTables() {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SHOW TABLES";
            $result = $mysql_database->executeQuery($query);
            
            $mysql_database = MysqlConnector::getInstance();
            $database_name = $mysql_database->getDatabaseName();
            $tables = array();
            
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row['Tables_in_' . $database_name];;
            }
            return $tables;
        }
        
        public function getColumns($table_name) {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = 'SHOW columns FROM ' . $table_name;
            $result = $mysql_database->executeQuery($query);
            
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