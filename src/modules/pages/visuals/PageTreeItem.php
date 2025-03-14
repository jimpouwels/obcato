<?php

namespace Obcato\Core\modules\pages\visuals;

use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\view\views\Visual;

class PageTreeItem extends Visual {

    private Page $page;
    private PageService $pageService;
    private Page $selectedPage;

    public function __construct(Page $page, Page $selectedPage) {
        parent::__construct();
        $this->page = $page;
        $this->pageService = PageInteractor::getInstance();
        $this->selectedPage = $selectedPage;
    }

    public function getTemplateFilename(): string {
        return "modules/pages/tree_item.tpl";
    }

    public function load(): void {
        $subPages = array();
        foreach ($this->pageService->getSubPages($this->page) as $subPage) {
            $treeItem = new PageTreeItem($subPage, $this->selectedPage);
            $subPages[] = $treeItem->render();
        }

        $this->assign("sub_pages", $subPages);
        $this->assign("name", $this->page->getName());
        $this->assign("show_in_navigation", $this->page->getShowInNavigation());
        $this->assign("published", $this->page->isPublished());
        $this->assign("page_id", $this->page->getId());
        $active = $this->selectedPage->getId() == $this->page->getId();
        $this->assign("active", $active);
    }

}