<?php

namespace Obcato\Core\admin\modules\pages;

use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\database\dao\PageDao;
use Obcato\Core\admin\database\dao\PageDaoMysql;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\pages\visuals\PageEditor;
use Obcato\Core\admin\modules\pages\visuals\PageTree;
use Obcato\Core\admin\view\views\ActionButtonAdd;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonDown;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ActionButtonUp;
use Obcato\Core\admin\view\views\ModuleVisual;

class PageModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/pages/head_includes.tpl";
    private ?Page $currentPage;
    private PageRequestHandler $pageRequestHandler;
    private PageDao $pageDao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
        $this->pageRequestHandler = new PageRequestHandler();
        $this->pageDao = PageDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/pages/root.tpl";
    }

    public function load(): void {
        $pageTree = new PageTree($this->getTemplateEngine(), $this->pageDao->getRootPage(), $this->currentPage);
        $pageEditor = new PageEditor($this->getTemplateEngine(), $this->currentPage);
        $this->assign("tree", $pageTree->render());
        $this->assign("editor", $pageEditor->render());
    }

    public function getActionButtons(): array {
        $buttons = array();
        $buttons[] = new ActionButtonSave($this->getTemplateEngine(), 'update_element_holder');
        if (!$this->currentPageIsHomepage()) {
            $buttons[] = new ActionButtonDelete($this->getTemplateEngine(), 'delete_element_holder');
        }
        $buttons[] = new ActionButtonAdd($this->getTemplateEngine(), 'add_element_holder');
        if ($this->currentPage->getId() != 1) {
            if (!$this->pageDao->isFirst($this->currentPage)) {
                $buttons[] = new ActionButtonUp($this->getTemplateEngine(), 'moveup_element_holder');
            }
            if (!$this->pageDao->isLast($this->currentPage)) {
                $buttons[] = new ActionButtonDown($this->getTemplateEngine(), 'movedown_element_holder');
            }
        }
        return $buttons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->getModuleIdentifier());
        $elementStaticsValues = array();
        $elementStatics = $this->currentPage->getElementStatics();
        foreach ($elementStatics as $element_static) {
            $elementStaticsValues[] = $element_static->render();
        }
        $this->getTemplateEngine()->assign("element_statics", $elementStaticsValues);
        return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->pageRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {
        $this->currentPage = $this->pageRequestHandler->getCurrentPage();
    }

    public function loadTabMenu(TabMenu $tabMenu): int {
        return 0;
    }

    private function currentPageIsHomepage(): bool {
        return $this->currentPage->getId() == 1;
    }
}