<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "database/dao/module_dao.php";
	require_once FRONTEND_REQUEST . "view/template_engine.php";
	require_once FRONTEND_REQUEST . "view/views/visual.php";
	require_once FRONTEND_REQUEST . "view/views/navigation_menu.php";
	require_once FRONTEND_REQUEST . "view/views/actions_menu.php";
	require_once FRONTEND_REQUEST . "view/views/notification_bar.php";
	
	class Cms extends Visual {
		
		private static $TEMPLATE = "system/cms.tpl";
		
		private $_template_engine;
		private $_module_visual;
		private $_website_title;
		private $_module_dao;
		
		public function __construct($module_visual, $website_title) {
			$this->_module_dao = ModuleDao::getInstance();
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_module_visual = $module_visual;
			$this->_website_title = $website_title;
		}
		
		public function render() {
			$navigation_menu = new NavigationMenu($this->_module_dao->getModuleGroups());
			$notification_bar = new NotificationBar();
			
			$template_engine = TemplateEngine::getInstance();
			if (!is_null($this->_module_visual)) {
				$actions_menu = new ActionsMenu($this->_module_visual->getActionButtons());
				$template_engine->assign("actions_menu", $actions_menu->render());
				$template_engine->assign("page_title", $this->_module_visual->getTitle());
				$template_engine->assign("module_head_includes", $this->_module_visual->getHeadIncludes());
			}
			
			$template_engine->assign("website_title", $this->_website_title);
			$template_engine->assign("navigation_menu", $navigation_menu->render());
			$template_engine->assign("notification_bar", $notification_bar->render());
			$template_engine->assign("content_pane", $this->renderContentPane($this->_module_visual));
			$template_engine->assign("system_version", SYSTEM_VERSION);
			$template_engine->assign("db_version", DB_VERSION);
			
			$this->_template_engine->display(self::$TEMPLATE);
		}
		
		private function renderContentPane() {
			$template_engine = TemplateEngine::getInstance();
			if (!is_null($this->_module_visual)) {
				return $this->_module_visual->render($this);
			} else {
				return $template_engine->fetch("system/home_wrapper.tpl");
			}
		}
		
	}

?>