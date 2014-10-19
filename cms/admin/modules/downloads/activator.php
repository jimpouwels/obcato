<?php

	
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "view/views/action_button.php";
    require_once CMS_ROOT . "modules/downloads/visuals/list_visual.php";
    require_once CMS_ROOT . "modules/downloads/visuals/editor_visual.php";
    require_once CMS_ROOT . "modules/downloads/visuals/search_box_visual.php";
    require_once CMS_ROOT . "modules/downloads/download_request_handler.php";

	class DownloadModuleVisual extends ModuleVisual {

        private static $TEMPLATE = "modules/downloads/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "downloads/head_includes.tpl";
        private $_current_download;
        private $_template_engine;
        private $_download_request_handler;

        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_download_request_handler = new DownloadRequestHandler();
        }

        public function render() {
            $this->_template_engine->assign('search_box', $this->renderSearchBox());
            if ($this->_current_download)
                $this->_template_engine->assign('editor', $this->renderEditor());
            else
                $this->_template_engine->assign("list", $this->renderList());
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }

        public function getActionButtons() {
            $action_buttons = array();
            if ($this->_current_download) {
                $action_buttons[] = new ActionButton('Opslaan', 'update_download', 'icon_apply');
                $action_buttons[] = new ActionButton('Verwijderen', 'delete_download', 'icon_delete');
            }
            $action_buttons[] = new ActionButton('Toevoegen', 'add_download', 'icon_add');
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
            $this->_current_download = $this->_download_request_handler->getCurrentDownload();
        }

        public function getTitle() {
            return "Downloads";
        }

        private function renderSearchBox() {
            $search_box = new SearchBoxVisual($this->_download_request_handler);
            return $search_box->render();
        }

        private function renderList() {
            $list = new ListVisual();
            return $list->render();
        }

        private function renderEditor() {
            $editor = new EditorVisual($this->_current_download);
            return $editor->render();
        }
    }
	
?>