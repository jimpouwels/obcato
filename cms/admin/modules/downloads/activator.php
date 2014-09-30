<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "/view/views/module_visual.php";
    require_once CMS_ROOT . "/view/views/action_button.php";
    require_once CMS_ROOT . "/modules/downloads/visuals/list_visual.php";
    require_once CMS_ROOT . "/modules/downloads/download_request_handler.php";

	class DownloadModuleVisual extends ModuleVisual {

        private static $TEMPLATE = "modules/downloads/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "downloads/head_includes.tpl";
        private $_template_engine;
        private $_download_request_handler;

        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_download_request_handler = new DownloadRequestHandler();
        }

        public function render() {
            $list = new ListVisual();
            $this->_template_engine->assign("list", $list->render());
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }

        public function getActionButtons()
        {
            $action_buttons = array();
            $action_buttons[] = new ActionButton("Toevoegen", "add_download", "icon_add");
            return $action_buttons;
        }

        public function getHeadIncludes() {
            return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_download_request_handler;
            return $request_handlers;
        }

        public function onPreHandled() {
        }

        public function getTitle() {
            return "Downloads";
        }
    }
	
?>