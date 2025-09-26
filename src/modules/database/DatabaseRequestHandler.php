<?php

namespace Obcato\Core\modules\database;

use Obcato\Core\core\form\FormException;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\database\MysqlResult;
use Obcato\Core\request_handlers\HttpRequestHandler;

class DatabaseRequestHandler extends HttpRequestHandler {

    private ?string $query = null;
    private ?MysqlResult $queryResult = null;
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
            $this->query = $form->getQuery();
            $queryRows = $this->mysqlConnector->executeQuery($this->query);
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