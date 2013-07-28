<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "visual/visual.php";
	require_once "libraries/system/template_engine.php";
	
	class TabMenu extends Visual {
	
		private static $TEMPLATE = "system/tab_menu.tpl";
		private $_tab_items;
		private $_current_tab;
		private $_template_engine;
	
		public function __construct($tab_items, $current_tab) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_tab_items = $tab_items;
			$this->_current_tab = $current_tab;
		}
	
		public function render() {
			$this->_template_engine->assign("tab_items", $this->_tab_items);
			$this->_template_engine->assign("current_tab", $this->_current_tab);
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}
	}

?>