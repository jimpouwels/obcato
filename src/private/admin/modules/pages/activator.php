<?php
require_once CMS_ROOT . "/modules/pages/model/Page.php";
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/pages/visuals/PageTree.php";
require_once CMS_ROOT . "/modules/pages/visuals/PageEditor.php";
require_once CMS_ROOT . "/modules/pages/PageRequestHandler.php";
require_once CMS_ROOT . '/database/dao/PageDaoMysql.php';

class PageModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/pages/head_includes.tpl";
    private ?Page $currentPage;
    private Module $pageModule;
    private PageRequestHandler $pageRequestHandler;
    private PageDao $pageDao;

    public function __construct(Module $pageModule) {
        parent::__construct($pageModule);
        $this->pageModule = $pageModule;
        $this->pageRequestHandler = new PageRequestHandler();
        $this->pageDao = PageDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/pages/root.tpl";
    }

    public function load(): void {
        $pageTree = new PageTree($this->pageDao->getRootPage(), $this->currentPage);
        $pageEditor = new PageEditor($this->currentPage);
        $this->assign("tree", $pageTree->render());
        $this->assign("editor", $pageEditor->render());
    }

    public function getActionButtons(): array {
        $buttons = array();
        $buttons[] = new ActionButtonSave('update_element_holder');
        if (!$this->currentPageIsHomepage()) {
            $buttons[] = new ActionButtonDelete('delete_element_holder');
        }
        $buttons[] = new ActionButtonAdd('add_element_holder');
        if ($this->currentPage->getId() != 1) {
            if (!$this->pageDao->isFirst($this->currentPage)) {
                $buttons[] = new ActionButtonUp('moveup_element_holder');
            }
            if (!$this->pageDao->isLast($this->currentPage)) {
                $buttons[] = new ActionButtonDown('movedown_element_holder');
            }
        }
        return $buttons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->pageModule->getIdentifier());
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

    public function getTabMenu(): ?TabMenu {
        return null;
    }

    private function currentPageIsHomepage(): bool {
        return $this->currentPage->getId() == 1;
    }

}
