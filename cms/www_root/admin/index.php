<?php

	// DIRECT ACCESS GRANTED
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');
	
	// INCLUDE SYSTEM CONSTANTS
	include_once FRONTEND_REQUEST . "libraries/system/constants.php";
	
	// RENDER BACKEND
	include_once FRONTEND_REQUEST . "backend.php";
	
	$backend = new Backend("site_administrator");
	$backend->start();
	
	// AUTHENTICATE
	//include_once FRONTEND_REQUEST . "core/data/session.php";
	//Session::isAuthenticated();
	
	// LOAD THE CURRENT MODULE
	//include_once FRONTEND_REQUEST . "pre_handlers/module_handler.php";
	
	// LAUNCH CURRENT MODULE PRE-HANDLER
	//if (isset($current_module) && !is_null($current_module)) {
	//	if (!is_null($current_module->getPreHandler()) && $current_module->getPreHandler() != '') {
	//		include_once FRONTEND_REQUEST . "modules/" . $current_module->getIdentifier() . "/" . $current_module->getPreHandler();
	//	}
	//}
	
	// INCLUDE SYSTEM PRE-HANDLERS
	//include_once FRONTEND_REQUEST . "pre_handlers/element_handler.php";
	//include_once FRONTEND_REQUEST . "pre_handlers/link_handler.php";

	// START HTML RENDERING
	//include_once FRONTEND_REQUEST . "html/basic_parts/index.php";
?>