<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/module.php";
	require_once "view/views/action_button.php";
	require_once "modules/authorization/authorization_pre_handler.php";
	require_once "modules/authorization/visuals/user_list.php";
	require_once "view/template_engine.php";

	class AuthorizationModuleVisual extends Module {
	
		private static $AUTHORIZATION_MODULE_TEMPLATE = "modules/authorization/root.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "modules/authorization/head_includes.tpl";
		private $_template_engine;
		private $_current_user;
		private $_authorization_pre_handler;
		
		public function __construct($article_module) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_authorization_pre_handler = new AuthorizationPreHandler();
		}
	
		public function render() {
			$user_list = new UserList($this->_current_user);
			$this->_template_engine->assign("user_list", $user_list->render());
			return $this->_template_engine->fetch(self::$AUTHORIZATION_MODULE_TEMPLATE);
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
			return $this->_template_engine->fetch(self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
			$this->_authorization_pre_handler->handle();
			$this->_current_user = $this->_authorization_pre_handler->getCurrentUser();
		}
		
		
	
	}
	
?>