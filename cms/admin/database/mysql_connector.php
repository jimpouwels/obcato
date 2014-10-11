<?php

	
	defined('_ACCESS') or die;

	class MysqlConnector {
	
		private static $instance;
		private $conn;
		private $_host;
		
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new MysqlConnector();				
			}
			return self::$instance;
		}
		
		private function __construct() {
			$this->_host = HOST;
			$this->_database_name = DATABASE_NAME;
			$this->conn = new mysqli($this->_host, USERNAME, PASSWORD, $this->_database_name) or die("Error connecting to MySQL database");
		}

		public function getConnection() {
			return $this->conn; 
		}

        public function prepareStatement($query) {
            return $this->conn->prepare($query);
        }

        public function executeStatement($statement) {
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result;
        }

        public function executeQuery($query) {
            $statement = $this->prepareStatement($query);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result;
        }

        public function executeSql($sql) {
            mysqli_multi_query($this->conn, $sql);
        }

        public function realEscapeString($value) {
            return $this->conn->real_escape_string($value);
        }

        public function getInsertId() {
            return $this->conn->insert_id;
        }
		
		public function getDatabaseName() {
			return $this->_database_name;
		}
		
		public function getHostName() {
			return $this->_host;
		}
		
		public function getDatabaseType() {
			return "MySQL";
		}
		
		public function getDatabaseVersion() {
			$query = "select version() AS version";
			$result = self::executeQuery($query);
			while ($row = $result->fetch_assoc()) {
				return $row['version'];
			}
		}
	}
	
?>