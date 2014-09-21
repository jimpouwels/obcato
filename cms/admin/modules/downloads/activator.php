<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "/view/views/module_visual.php";
    require_once CMS_ROOT . "/view/views/action_button.php";

	class DownloadModuleVisual extends ModuleVisual {

        private $_template_engine;
        private static $TEMPLATE = "modules/downloads/root.tpl";

        public function __construct() {
           $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }

        public function getActionButtons()
        {
            $action_buttons = array();
            $action_buttons[] = new ActionButton("Toevoegen", "add_download", "icon_add");
            return $action_buttons;
        }

        public function getHeadIncludes() {
        }

        public function getRequestHandlers() {
        }

        public function onPreHandled() {
        }

        public function getTitle() {
            return "Downloads";
        }
    }
	
?>