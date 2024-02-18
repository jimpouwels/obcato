<?php

class MysqlConnector {

    private static ?MysqlConnector $instance = null;
    private mysqli $conn;
    private string $host;
    private string $databaseName;

    public static function getInstance(): MysqlConnector {
        if (is_null(self::$instance)) {
            self::$instance = new MysqlConnector();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->host = HOST;
        $this->databaseName = DATABASE_NAME;
        $this->conn = new mysqli($this->host, USERNAME, PASSWORD, $this->databaseName) or die("Error connecting to MySQL database");
    }

    public function getConnection(): mysqli {
        return $this->conn;
    }

    public function prepareStatement(string $query): mysqli_stmt {
        return $this->conn->prepare($query);
    }

    public function executeStatement(mysqli_stmt $statement): bool|mysqli_result {
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }

    public function executeQuery(string $query): bool|mysqli_result {
        $statement = $this->prepareStatement($query);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }

    public function executeSql(string $sql): void {
        mysqli_multi_query($this->conn, $sql);
    }

    public function realEscapeString(string $value): string {
        return $this->conn->real_escape_string($value);
    }

    public function getNumberOfAffectedRows(): int {
        return mysqli_affected_rows($this->conn);
    }

    public function getInsertId(): int {
        return mysqli_insert_id($this->conn);
    }

    public function getDatabaseName(): string {
        return $this->databaseName;
    }

    public function getHostName(): string {
        return $this->host;
    }

    public function getDatabaseType(): string {
        return "MySQL";
    }

    public function getDatabaseVersion(): string {
        $query = "select version() AS version";
        $result = self::executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return $row['version'];
        }
        return "";
    }
}

?>
