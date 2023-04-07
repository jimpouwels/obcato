<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "modules/database/query_form.php";

    class DatabasePreHandler extends HttpRequestHandler {

        private $_query;
        private $_query_result;
        private $_affected_rows;
        private $_mysql_connector;

        public function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public function handleGet() {
        }

        public function handlePost() {
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

        public function getQuery() {
            return $this->_query;
        }

        public function getFields() {
            $fields = array();
            if (!$this->_query_result) return $fields;
            foreach ($this->_query_result->fetch_fields() as $field)
                $fields[] = $field->name;
            return $fields;
        }

        public function getValues() {
            if (is_null($this->_query_result)) return array();
            $values = array();
            while ($result_row = $this->_query_result->fetch_assoc()) {
                $row = array();
                foreach ($result_row as $cell)
                    $row[] = $cell;
                $values[] = $row;
            }
            return $values;
        }

        public function getAffectedRows() {
            return $this->_affected_rows;
        }

    }

?>
