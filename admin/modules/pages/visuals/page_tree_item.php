<?php
defined('_ACCESS') or die;

class PageTreeItem extends Visual {

    private Page $_page;
    private PageDao $_page_dao;
    private Page $_selected_page;

    public function __construct(Page $page, Page $selected_page) {
        parent::__construct();
        $this->_page = $page;
        $this->_page_dao = PageDaoMysql::getInstance();
        $this->_selected_page = $selected_page;
    }

    public function getTemplateFilename(): string {
        return "modules/pages/tree_item.tpl";
    }

    public function load(): void {
        $sub_pages = array();
        foreach ($this->_page_dao->getSubPages($this->_page) as $sub_page) {
            $tree_item = new PageTreeItem($sub_page, $this->_selected_page);
            $sub_pages[] = $tree_item->render();
        }

        $this->assign("sub_pages", $sub_pages);
        $this->assign("title", $this->_page->getTitle());
        $this->assign("show_in_navigation", $this->_page->getShowInNavigation());
        $this->assign("published", $this->_page->isPublished());
        $this->assign("page_id", $this->_page->getId());
        $active = $this->_selected_page->getId() == $this->_page->getId();
        $this->assign("active", $active);
    }

}

?>
