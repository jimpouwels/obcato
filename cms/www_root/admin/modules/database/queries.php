<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/renderers/form_renderer.php";
	include_once FRONTEND_REQUEST . "libraries/renderers/main_renderer.php";
	include_once FRONTEND_REQUEST . "database/mysql_connector.php";
	include_once FRONTEND_REQUEST . "libraries/validators/form_validator.php";
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$query = FormValidator::checkEmpty('query', 'U heeft geen query ingevoerd');
	}
	if (isset($query) && !is_null($query) && $query != '') {
		$mysql_database = MysqlConnector::getInstance(); 
		
		$result = $mysql_database->executeSelectQuery($query);
	}
	
	// information message
	MainRenderer::renderWarningMessage("Let op! Wees voorzichtig met het uitvoeren van queries! Data kan onherstelbaar verloren gaan!");
?>
