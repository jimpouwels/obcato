<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/module_visual.php";
require_once CMS_ROOT . "modules/downloads/visuals/list_visual.php";
require_once CMS_ROOT . "modules/downloads/visuals/editor_visual.php";
require_once CMS_ROOT . "modules/downloads/visuals/search_box_visual.php";
require_once CMS_ROOT . "modules/downloads/download_request_handler.php";

class DownloadModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "downloads/head_includes.tpl";
    private ?Download $_current_download;
    private DownloadRequestHandler $_download_request_handler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->_module = $module;
        $this->_download_request_handler = new DownloadRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/downloads/root.tpl";
    }

    public function load(): void {
        $this->assign('search_box', $this->renderSearchBox());
        if ($this->_current_download) {
            $this->assign('editor', $this->renderEditor());
        } else {
            $this->assign("list", $this->renderList());
        }
    }

    private function renderSearchBox(): string {
        $search_box = new SearchBoxVisual($this->_download_request_handler);
        return $search_box->render();
    }

    private function renderEditor(): string {
        $editor = new EditorVisual($this->_current_download);
        return $editor->render();
    }

    private function renderList(): string {
        $list = new ListVisual($this->_download_request_handler);
        return $list->render();
    }

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->_current_download) {
            $action_buttons[] = new ActionButtonSave('update_download');
            $action_buttons[] = new ActionButtonDelete('delete_download');
        }
        $action_buttons[] = new ActionButtonAdd('add_download');
        return $action_buttons;
    }

    public function renderHeadIncludes(): string {
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        $request_handlers[] = $this->_download_request_handler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {
        $this->_current_download = $this->_download_request_handler->getCurrentDownload();
    }

    function getTabMenu(): ?TabMenu {
        // TODO: Implement getTabMenu() method.
    }
}

?>