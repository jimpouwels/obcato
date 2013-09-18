<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/template_engine.php";
	require_once FRONTEND_REQUEST . "view/views/search.php";
	
	class Popup extends Visual {
		
		private static $TEMPLATE = "system/popup.tpl";
		private $_popup_type;		
		private $_template_engine;
		
		public function __construct($popup_type) {
			$this->_popup_type = $popup_type;
			$this->_template_engine = TemplateEngine::getInstance();
		}
		
		public function render() {
			$content = null;
			if ($this->_popup_type == "search") {
				$content = new Search();
			}
			$this->_template_engine->assign("content", $content->render());
			$this->_template_engine->display(self::$TEMPLATE);
		}
		
	}

?>