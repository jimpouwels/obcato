<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/request_handlers/http_request_handler.php";
	
	abstract class ModuleRequestHandler extends HttpRequestHandler {
				
		public function getCurrentTabId() {
			$this->getModuleTabFromGetRequest();
			return $this->getModuleTabFromSession();
		}
		
		public function setRequestError($error_identifier, $message) {
			global $errors;
			$errors[$error_identifier] = $message;
		}
		
		public function getErrorCount() {
			global $errors;
			return count($errors);
		}
		
		private function getModuleTabFromGetRequest() {
			if (isset($_GET["module_tab"])) {
				$_SESSION["module_tab"] = $_GET["module_tab"];
			}
		}
		
		private function getModuleTabFromSession() {
			$current_module_tab = 0;
			if (isset($_SESSION["module_tab"])) {
				$current_module_tab = $_SESSION["module_tab"];
			}
			return $current_module_tab;
		}

	}