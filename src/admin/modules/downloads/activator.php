<?php

namespace Obcato\Core\admin\modules\downloads;

use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\downloads\model\Download;
use Obcato\Core\admin\modules\downloads\visuals\EditorVisual;
use Obcato\Core\admin\modules\downloads\visuals\ListVisual;
use Obcato\Core\admin\modules\downloads\visuals\SearchBoxVisual;
use Obcato\Core\admin\view\views\ActionButtonAdd;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ModuleVisual;

class DownloadModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "downloads/head_includes.tpl";
    private ?Download $currentDownload;
    private DownloadRequestHandler $requestHandler;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
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
        return (new SearchBoxVisual($this->getTemplateEngine(), $this->requestHandler))->render();
    }

    private function renderEditor(): string {
        return (new EditorVisual($this->getTemplateEngine(), $this->currentDownload))->render();
    }

    private function renderList(): string {
        return (new ListVisual($this->getTemplateEngine(), $this->requestHandler))->render();
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->currentDownload) {
            $actionButtons[] = new ActionButtonSave($this->getTemplateEngine(), 'update_download');
            $actionButtons[] = new ActionButtonDelete($this->getTemplateEngine(), 'delete_download');
        }
        $actionButtons[] = new ActionButtonAdd($this->getTemplateEngine(), 'add_download');
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

    function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }
}