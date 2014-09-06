<?php

	// No direct access
	defined('_ACCESS') or die;
		
	require_once "view/smarty/Smarty.class.php";
	
	class TemplateEngine
	{
	
		// singleton
		private static $instance;
		
		/*
			Creates a new instance of the TemplateEngineLoader class.
		*/
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new Smarty();
				self::$instance->template_dir = TEMPLATE_ENGINE_DIR . "/templates";
				self::$instance->compile_dir = TEMPLATE_ENGINE_DIR . "/compiled_templates";
				self::$instance->cache_dir = TEMPLATE_ENGINE_DIR . "/cache";			
			}
			return self::$instance;
		}
		
		private function __construct() {
		}		
	
	}
	
?>