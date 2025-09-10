<?php

namespace Obcato\Core\modules\downloads;

use Obcato\Core\core\model\Module;
use Obcato\Core\modules\downloads\model\Download;
use Obcato\Core\modules\downloads\visuals\EditorVisual;
use Obcato\Core\modules\downloads\visuals\ListVisual;
use Obcato\Core\modules\downloads\visuals\SearchBoxVisual;
use Obcato\Core\view\views\ActionButtonAdd;
use Obcato\Core\view\views\ActionButtonDelete;
use Obcato\Core\view\views\ActionButtonSave;
use Obcato\Core\view\views\ModuleVisual;
use Obcato\Core\view\views\TabMenu;

class DownloadModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "downloads/templates/head_includes.tpl";
    private ?Download $currentDownload;
    private DownloadRequestHandler $requestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->requestHandler = new DownloadRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "downloads/templates/root.tpl";
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
        return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->requestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {
        $this->currentDownload = $this->requestHandler->getCurrentDownload();
    }

    function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }
}