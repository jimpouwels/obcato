<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/visual/visual.php";
	require_once "libraries/system/template_engine.php";
	
	class WarningMessage extends Visual {
	
		private static $TEMPLATE = "system/warning_message.tpl";
		private $_message;
		private $_template_engine;
	
		public function __construct($message) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_message = $message;
		}
	
		public function render() {
			$this->_template_engine->assign("message", $this->_message);
			
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}	
	}

?>