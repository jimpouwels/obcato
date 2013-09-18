<?php
	
	require_once FRONTEND_REQUEST . "core/data/session.php";
	require_once FRONTEND_REQUEST . "view/request_handlers/module_request_handler.php";
	
	class LogoutPreHandler extends ModuleRequestHandler {
	
		public function handleGet() {
			Session::logOut($_SESSION['username']);
		}
		
		public function handlePost() {
		}
		
	}
	
?>