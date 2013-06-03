<?php
	
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');
	
	include_once "backend.php";
	
	// AUTHENTICATE
	$backend = new Backend("site_administrator");
	$backend->isAuthenticated();
	
	if (isset($_GET['static']) && $_GET['static'] != '') {
		$base = Settings::find()->getStaticDir();
		
		$file_name = $_GET['static'];
		$path = $base . $file_name;
		/* This script takes a variable named $path strips off the last 3 characters to see what the extension is,
		and processes it accordingly */
		$splits = explode('.', $file_name);
		$extension = $splits[count($splits) - 1];
		
		if ($extension == "jpg") {
			header("Content-Type: image/jpeg");
		} else if ($extension == "gif"){
			header("Content-Type: image/gif");
		} else if ($extension == "png"){
			header("Content-Type: img/png");
		} else if ($extension == "css"){
			header("Content-Type: text/css");
		} else if ($extension == "js"){
			header("Content-Type: text/javascript");
		}
		
		readfile($path);
	} 
?>