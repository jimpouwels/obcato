<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/database/QueryForm.php";

class DatabaseRequestHandler extends HttpRequestHandler {

    private ?string $query = null;
    private ?mysqli_result $queryResult = null;
    private int $affectedRows = 0;
    private MysqlConnector $mysqlConnector;

    public function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $form = new QueryForm();
            $form->loadFields();
            $queryRows = $this->mysqlConnector->executeQuery($form->getQuery());
            if ($queryRows && !is_bool($queryRows)) {
                $this->queryResult = $queryRows;
                $this->affectedRows = $this->mysqlConnector->getNumberOfAffectedRows();
            }
            $this->sendSuccessMessage("Query succesvol uitgevoerd");
        } catch (FormException $e) {
            $this->sendErrorMessage("Er is geen query uitgevoerd");
        }
    }

    public function getQuery(): ?string {
        return $this->query;
    }

    public function getFields(): array {
        $fields = array();
        if (!$this->queryResult) return $fields;
        foreach ($this->queryResult->fetch_fields() as $field) {
            $fields[] = $field->name;
        }
        return $fields;
    }

    public function getValues(): array {
        if (!$this->queryResult) return array();
        $values = array();
        while ($resultRow = $this->queryResult->fetch_assoc()) {
            $row = array();
            foreach ($resultRow as $cell) {
                $row[] = $cell;
            }
            $values[] = $row;
        }
        return $values;
    }

    public function getAffectedRows(): int {
        return $this->affectedRows;
    }

}

?>
