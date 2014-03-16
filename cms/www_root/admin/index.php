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

?>