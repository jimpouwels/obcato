<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/http_request_handler.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/database/query_form.php";

class DatabaseRequestHandler extends HttpRequestHandler {

    private ?string $_query = null;
    private ?mysqli_result $_query_result = null;
    private int $_affected_rows = 0;
    private MysqlConnector $_mysql_connector;

    public function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $form = new QueryForm();
        try {
            $form->loadFields();
            $query_rows = $this->_mysql_connector->executeQuery($form->getQuery());
            if (isset($query_rows) && !is_bool($query_rows)) {
                $this->_query_result = $query_rows;
                $this->_affected_rows = $this->_mysql_connector->getNumberOfAffectedRows();
            }
            $this->sendSuccessMessage("Query succesvol uitgevoerd");
        } catch (FormException $e) {
            $this->sendErrorMessage("Er is geen query uitgevoerd");
        }
    }

    public function getQuery(): ?string {
        return $this->_query;
    }

    public function getFields(): array {
        $fields = array();
        if (!$this->_query_result) return $fields;
        foreach ($this->_query_result->fetch_fields() as $field) {
            $fields[] = $field->name;
        }
        return $fields;
    }

    public function getValues(): array {
        if (is_null($this->_query_result)) return array();
        $values = array();
        while ($result_row = $this->_query_result->fetch_assoc()) {
            $row = array();
            foreach ($result_row as $cell) {
                $row[] = $cell;
            }
            $values[] = $row;
        }
        return $values;
    }

    public function getAffectedRows(): int {
        return $this->_affected_rows;
    }

}

?>
