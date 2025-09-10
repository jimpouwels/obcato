<?php

namespace Obcato\Core\modules\sitewide_pages;

use Obcato\Core\core\model\Module;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\sitewide_pages\visuals\SitewidePageList;
use Obcato\Core\view\views\ActionButtonDelete;
use Obcato\Core\view\views\ModuleVisual;
use Obcato\Core\view\views\TabMenu;

class SitewidePagesModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "sitewide_pages/templates/head_includes.tpl";
    private Module $module;
    private SitewidePagesRequestHandler $requestHandler;
    private PageService $pageService;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->module = $module;
        $this->requestHandler = new SitewidePagesRequestHandler();
        $this->pageDao = PageInteractor::getInstance();
    }

    public function getTemplateFilename(): string {
        return "sitewide_pages/templates/root.tpl";
    }

    public function load(): void {
        $list = new SitewidePageList();
        $this->assign("list", $list->render());
    }

    public function getActionButtons(): array {
        $buttons = array();
        $buttons[] = new ActionButtonDelete('remove_sitewide_pages');
        return $buttons;
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
    }

    public function loadTabMenu(TabMenu $tabMenu): int {
        return 0;
    }

}