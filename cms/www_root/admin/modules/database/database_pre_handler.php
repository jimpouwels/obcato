<?php	// No direct access	defined('_ACCESS') or die;		require_once FRONTEND_REQUEST . "view/request_handlers/module_request_handler.php";	require_once FRONTEND_REQUEST . "database/mysql_connector.php";	require_once FRONTEND_REQUEST . "libraries/validators/form_validator.php";		class DatabasePreHandler extends ModuleRequestHandler {		const QUERY_POST_KEY = "query";		private $_query;		private $_query_result;		private $_affected_rows;		public function __construct() {			$this->_mysql_database = MysqlConnector::getInstance(); 		}		public function handleGet() {		}		public function handlePost() {			$this->_query = FormValidator::checkEmpty(self::QUERY_POST_KEY, "U heeft geen query ingevoerd");			if (!is_null($this->_query) && $this->_query != "") {				$query_rows = $this->_mysql_database->executeSelectQuery($this->_query);				if (isset($query_rows) && !is_bool($query_rows)) {					$this->_query_result = $query_rows;					$this->_affected_rows = mysql_affected_rows();				}			}		}				public function getQuery() {			return $this->_query;		}				public function getFields() {			$fields = array();			for ($i = 0; $i < $this->getNumberOfResults(); $i++) {				$fields[] = mysql_field_name($this->_query_result, $i);			}			return $fields;		}				public function getValues() {			if (is_null($this->_query_result)) return array();			$values = array();			while ($result_row = mysql_fetch_row($this->_query_result)) {				$row = array();				foreach ($result_row as $cell) {					$row[] = $cell;				}				$values[] = $row;			}			return $values;		}				public function getAffectedRows() {			return $this->_affected_rows;		}		private function getNumberOfResults() {			if (is_null($this->_query_result))				return 0;			else				return mysql_num_fields($this->_query_result);		}	}	?>