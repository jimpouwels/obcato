<?php
	
	require_once "core/data/session.php";
	require_once "view/request_handlers/module_request_handler.php";
	
	class LogoutPreHandler extends ModuleRequestHandler {
	
		public function handleGet() {
			Session::logOut($_SESSION['username']);
		}
		
		public function handlePost() {
		}
		
	}
	
?>