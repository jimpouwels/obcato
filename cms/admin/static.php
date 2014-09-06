<?php
	
	define("_ACCESS", "GRANTED");
    define("CMS_ROOT", '');

    require_once CMS_ROOT . "/database_config.php";
	require_once CMS_ROOT . "/constants.php";
	require_once CMS_ROOT . "/backend.php";
	require_once CMS_ROOT . "/pre_handlers/statics_request_handler.php";
	
	$backend = new Backend("site_administrator");
	$backend->isAuthenticated();
	
	$statics_request_handler = new StaticsRequestHandler();
	$statics_request_handler->handle();
?>