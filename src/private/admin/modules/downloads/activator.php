<?php
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/downloads/visuals/ListVisual.php";
require_once CMS_ROOT . "/modules/downloads/visuals/EditorVisual.php";
require_once CMS_ROOT . "/modules/downloads/visuals/SearchBoxVisual.php";
require_once CMS_ROOT . "/modules/downloads/DownloadRequestHandler.php";

class DownloadModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "downloads/head_includes.tpl";
    private ?Download $currentDownload;
    private DownloadRequestHandler $requestHandler;
    private Module $module;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->module = $module;
        $this->requestHandler = new DownloadRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/downloads/root.tpl";
    }

    public function load(): void {
        $this->assign('search_box', $this->renderSearchBox());
        if ($this->currentDownload) {
            $this->assign('editor', $this->renderEditor());
        } else {
            $this->assign("list", $this->renderList());
        }
    }

    private function renderSearchBox(): string {
        return (new SearchBoxVisual($this->requestHandler))->render();
    }

    private function renderEditor(): string {
        return (new EditorVisual($this->currentDownload))->render();
    }

    private function renderList(): string {
        return (new ListVisual($this->requestHandler))->render();
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->currentDownload) {
            $actionButtons[] = new ActionButtonSave('update_download');
            $actionButtons[] = new ActionButtonDelete('delete_download');
        }
        $actionButtons[] = new ActionButtonAdd('add_download');
        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->requestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {
        $this->currentDownload = $this->requestHandler->getCurrentDownload();
    }

    function getTabMenu(): ?TabMenu {}
}