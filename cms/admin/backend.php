<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "/database/dao/settings_dao.php";
	require_once CMS_ROOT . "/pre_handlers/link_pre_handler.php";
	require_once CMS_ROOT . "/pre_handlers/element_pre_handler.php";
	require_once CMS_ROOT . "/pre_handlers/module_pre_handler.php";
	require_once CMS_ROOT . "/view/views/cms.php";
	require_once CMS_ROOT . "/core/data/settings.php";
	require_once CMS_ROOT . "/core/data/session.php";
	require_once CMS_ROOT . "/view/views/popup.php";
	
	class Backend {
	
		private $_identifier;
		private $_session;
		private $_pre_handlers;
		private $_settings;
		private $_current_module;
		private $_module_visual;
	
		public function __construct($identifier) {
			$this->_identifier = $identifier;
			$this->_session = new Session();
			$this->_settings = SettingsDao::getInstance()->getSettings();
			$this->initializePreHandlers();
		}
		
		public function start() {
			$this->isAuthenticated();
			$this->runPreHandlers();
			$this->runModulePreHandler();
			$this->renderCms();
		}
		
		public function isAuthenticated() {
			if (!$this->_session->isAuthenticated())
				$this->redirectToLoginPage();
		}
		
		/*
			Callback invocation for ModulePreHandler.
		*/
		public function setCurrentModule($current_module) {
			if (!is_null($current_module)) {
				$this->_current_module = $current_module;
				require_once CMS_ROOT . "/modules/" . $this->_current_module->getIdentifier() . "/activator.php";
				$class = $this->_current_module->getClass();
				$this->_module_visual = new $class($this->_current_module);
			}
		}
		
		private function renderCms() {
			if ($this->isPopupView())
				$this->renderPopupView();
			else
				$this->renderCmsView();
		}
		
		private function renderCmsView() {
			$cms = new Cms($this->_module_visual, $this->_settings->getWebsiteTitle());
			$cms->render();
		}

		private function renderPopupView() {
			$popup = new Popup($_GET['popup']);
			$popup->render();
		}
		
		private function runModulePreHandler() {
			if (!is_null($this->_module_visual)) {
				foreach ($this->_module_visual->getPreHandlers() as $pre_handler)
					$pre_handler->handle();
				$this->_module_visual->onPreHandled();
			}
		}
		
		private function initializePreHandlers() {
			$this->_pre_handlers = array();
			$this->_pre_handlers[] = new LinkPreHandler();
			$this->_pre_handlers[] = new ElementPreHandler();
			$this->_pre_handlers[] = new ModulePreHandler($this);
		}
		
		private function runPreHandlers() {
			foreach ($this->_pre_handlers as $pre_handler) {
				if ($pre_handler instanceof PreHandler)
					$pre_handler->handle();
			}
		}
		
		private function redirectToLoginPage() {
			session_destroy();
			$org_url = null;
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