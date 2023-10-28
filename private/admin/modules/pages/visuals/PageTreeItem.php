<?php

class PageTreeItem extends Visual {

    private Page $page;
    private PageDao $pageDao;
    private Page $selectedPage;

    public function __construct(Page $page, Page $selectedPage) {
        parent::__construct();
        $this->page = $page;
        $this->pageDao = PageDaoMysql::getInstance();
        $this->selectedPage = $selectedPage;
    }

    public function getTemplateFilename(): string {
        return "modules/pages/tree_item.tpl";
    }

    public function load(): void {
        $subPages = array();
        foreach ($this->pageDao->getSubPages($this->page) as $subPage) {
            $treeItem = new PageTreeItem($subPage, $this->selectedPage);
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
