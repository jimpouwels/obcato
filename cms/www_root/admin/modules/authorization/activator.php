<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "core/data/module.php";
	require_once FRONTEND_REQUEST . "view/views/action_button.php";
	require_once FRONTEND_REQUEST . "modules/authorization/authorization_pre_handler.php";

	class AuthorizationModuleVisual extends Module {
	
		private $_current_user;
		private $_authorization_pre_handler;
		
		public function __construct($article_module) {
			$this->_authorization_pre_handler = new AuthorizationPreHandler();
		}
	
		public function render() {
		}
	
		public function getActionButtons() {
			$action_buttons = array();
			if (!is_null($this->_current_user)) {
				$action_buttons[] = new ActionButton("Opslaan", "update_user", "icon_apply");
				if (!$this->_current_user->isLoggedInUser()) {
					$action_buttons[] = new ActionButton("Verwijderen", "delete_user", "icon_delete");
				}
			}
			$action_buttons[] = new ActionButton("Toevoegen", "add_user", "icon_add");
			return $action_buttons;
		}
		
		public function getHeadIncludes() {
		}
		
		public function preHandle() {
			$this->_authorization_pre_handler->handle();
			$this->_current_user = $this->_authorization_pre_handler->getCurrentUser();
		}
		
		
	
	}
	
?>