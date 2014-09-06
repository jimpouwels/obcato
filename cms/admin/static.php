<?php
	
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');

    require_once "database_config.php";
	require_once "constants.php";
	require_once "backend.php";
	require_once "pre_handlers/statics_request_handler.php";
	
	$backend = new Backend("site_administrator");
	$backend->isAuthenticated();
	
	$statics_request_handler = new StaticsRequestHandler();
	$statics_request_handler->handle();
?>