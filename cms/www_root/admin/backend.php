<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/session.php";
	require_once "pre_handlers/link_pre_handler.php";
	require_once "pre_handlers/element_pre_handler.php";
	require_once "pre_handlers/module_pre_handler.php";
	require_once "core/visual/cms.php";
	require_once "core/visual/popup.php";
	
	class Backend {
	
		private $_identifier;
		private $_session;
		private $_pre_handlers;
		private $_settings;
		private $_current_module;
	
		public function __construct($identifier) {
			$this->_identifier = $identifier;
			$this->_session = new Session();
			$this->_settings = Settings::find();
			
			$this->_pre_handlers = array();
			$this->_pre_handlers[] = new LinkPreHandler();
			$this->_pre_handlers[] = new ElementPreHandler();
			$this->_pre_handlers[] = new ModulePreHandler($this);
		}
		
		public function start() {
			$module_visual = null;;
		
			$this->isAuthenticated();
			$this->runPreHandlers();
			if (!is_null($this->_current_module)) {
				require_once "modules/" . $this->_current_module->getIdentifier() . "/activator.php";
				$class = $this->_current_module->getClass();
				$module_visual = new $class($this->_current_module);
				$module_visual->preHandle();
			}
			if ($this->isPopupView()) {
				$popup = new Popup($_GET['popup']);
				$popup->render();
			} else {
				$cms = new Cms($module_visual, $this->_settings->getWebsiteTitle());
				$cms->render();
			}
		}
		
		public function isAuthenticated() {
			$authenticated = $this->_session->isAuthenticated();
			if (!$authenticated) {
				$this->redirectToLoginPage();
			}
		}
		
		/*
			Callback invocation for ModulePreHandler.
		*/
		public function setCurrentModule($current_module) {
			$this->_current_module = $current_module;
		}
		
		private function runPreHandlers() {
			foreach ($this->_pre_handlers as $pre_handler) {
				if ($pre_handler instanceof PreHandler) {
					$pre_handler->handle();
				}
			}
		}
		
		private function redirectToLoginPage() {
			session_destroy();
			$org_url = NULL;
			if ($_SERVER['REQUEST_URI'] != '/admin/') {
				$org_url = '?org_url=' . urlencode($_SERVER['REQUEST_URI']);
			}
			header('Location: /admin/login.php' . $org_url);
			exit();
		}
				
		private function isPopupView() {
			return isset($_GET['popup']);
		}
		
	}