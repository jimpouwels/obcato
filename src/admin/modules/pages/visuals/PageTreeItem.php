<?php

namespace Obcato\Core\admin\modules\pages\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\pages\service\PageInteractor;
use Obcato\Core\admin\modules\pages\service\PageService;

class PageTreeItem extends Visual {

    private Page $page;
    private PageService $pageService;
    private Page $selectedPage;

    public function __construct(TemplateEngine $templateEngine, Page $page, Page $selectedPage) {
        parent::__construct($templateEngine);
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
            $treeItem = new PageTreeItem($this->getTemplateEngine(), $subPage, $this->selectedPage);
            $subPages[] = $treeItem->render();
        }

        $this->assign("sub_pages", $subPages);
        $this->assign("title", $this->page->getTitle());
        $this->assign("show_in_navigation", $this->page->getShowInNavigation());
        $this->assign("published", $this->page->isPublished());
        $this->assign("page_id", $this->page->getId());
        $active = $this->selectedPage->getId() == $this->page->getId();
        $this->assign("active", $active);
    }

}