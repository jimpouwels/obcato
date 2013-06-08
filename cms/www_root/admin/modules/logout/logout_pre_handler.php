<?php
	
	require_once "core/data/session.php";
	require_once "core/http/module_request_handler.php";
	
	class LogoutPreHandler extends ModuleRequestHandler {
	
		public function handleGet() {
			Session::logOut($_SESSION['username']);
		}
		
		public function handlePost() {
		}
		
	}
	
?>