<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
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
        private $_module;

        public function __construct($module) {
            parent::__construct($module);
            $this->_module = $module;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_download_request_handler = new DownloadRequestHandler();
        }

        public function renderVisual(): string {
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
                $action_buttons[] = new ActionButtonSave('update_download');
                $action_buttons[] = new ActionButtonDelete('delete_download');
            }
            $action_buttons[] = new ActionButtonAdd('add_download');
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

        private function renderSearchBox() {
            $search_box = new SearchBoxVisual($this->_download_request_handler);
            return $search_box->render();
        }

        private function renderList() {
            $list = new ListVisual($this->_download_request_handler);
            return $list->render();
        }

        private function renderEditor() {
            $editor = new EditorVisual($this->_current_download);
            return $editor->render();
        }
    }
    
?>