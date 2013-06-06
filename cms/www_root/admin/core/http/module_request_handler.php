<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/http/http_request_handler.php";
	
	abstract class ModuleRequestHandler extends HttpRequestHandler {
		
		public function getCurrentTabId() {
			$current_module_tab = 0;
			
			if (isset($_GET['module_tab'])) {
				$_SESSION['module_tab'] = $_GET['module_tab'];
			}
			if (isset($_SESSION['module_tab'])) {
				$current_module_tab = $_SESSION['module_tab'];
			}
			return $current_module_tab;
		}

	}