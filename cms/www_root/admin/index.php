<?php

	// DIRECT ACCESS GRANTED
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');

	require_once "includes.php";
	require_once "constants.php";
	require_once "backend.php";
	
	$backend = new Backend("site_administrator");
	$backend->start();

?>