<?php

	// No direct access
	defined('_ACCESS') or die;
		
	require_once CMS_ROOT . "/view/smarty/Smarty.class.php";
    require_once CMS_ROOT . "/database/dao/settings_dao.php";
	
	class TemplateEngine
	{
	
		// singleton
		private static $instance;

		/*
			Creates a new instance of the TemplateEngineLoader class.
		*/
		public static function getInstance() {
			if (is_null(self::$instance)) {
                $settings_dao = SettingsDao::getInstance();
                $backend_template_dir = $settings_dao->getSettings()->getBackendTemplateDir();
				self::$instance = new Smarty();
				self::$instance->template_dir = $backend_template_dir . "/templates";
				self::$instance->compile_dir = $backend_template_dir . "/compiled_templates";
				self::$instance->cache_dir = $backend_template_dir . "/cache";
			}
			return self::$instance;
		}
		
		private function __construct() {
		}		
	
	}
	
?>