<?php
	// No direct access
	defined('_ACCESS') or die;

	require_once "database/dao/template_dao.php";
	require_once "libraries/validators/form_validator.php";
	require_once "libraries/handlers/form_handler.php";
	require_once "libraries/system/notifications.php";
	require_once "view/request_handlers/module_request_handler.php";
	
	class TemplatePreHandler extends ModuleRequestHandler {
	
		private static $TEMPLATE_ID_GET = "template";
		private static $TEMPLATE_ID_POST = "template_id";

		private $_template_dao;
		private $_current_template;
		
		public function __construct() {
			$this->_template_dao = TemplateDao::getInstance();
		}
	
		public function handleGet() {
			if ($this->isCurrentTemplateShown()) {
				$this->_current_template = $this->getTemplateFromGetRequest();
			}
		}
		
		public function handlePost() {
			$this->_current_template = $this->getTemplateFromPostRequest();
			// handle template actions
		}
		
		public function getCurrentTemplate() {
			return $this->_current_template;
		}

		private function getTemplateFromPostRequest() {
			return $this->_template_dao->getTemplate($_POST[self::$TEMPLATE_ID_POST]);
		}
		
		private function getTemplateFromGetRequest() {
			return $this->_template_dao->getTemplate($_GET[self::$TEMPLATE_ID_GET]);
		}
		
		private function isCurrentTemplateShown() {
			return isset($_GET[self::$TEMPLATE_ID_GET]);
		}

	}
?>