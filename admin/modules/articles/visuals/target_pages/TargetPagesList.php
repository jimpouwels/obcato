<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/PagePicker.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";

class TargetPagesList extends Panel {

    private ArticleDao $_article_dao;

    public function __construct() {
        parent::__construct('Beschikbare doelpagina\'s', 'target_pages_fieldset');
        $this->_article_dao = ArticleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/target_pages/list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("target_pages", $this->getTargetPages());
        $data->assign("default_target_page", $this->getDefaultTargetPage());

        $page_picker = new PagePicker("add_target_page_ref", "", null, "update_target_pages");
        $data->assign("page_picker", $page_picker->render());
    }

    private function getDefaultTargetPage(): array {
        $target_page = $this->_article_dao->getDefaultTargetPage();
        $target_page_value = null;
        if (!is_null($target_page)) {
            $target_page_value = $this->toArray($target_page);
        }
        return $target_page_value;
    }

    private function getTargetPages(): array {
        $target_pages = array();
        foreach ($this->_article_dao->getTargetPages() as $target_page) {
            $target_pages[] = $this->toArray($target_page);
        }
        return $target_pages;
    }

    private function toArray($target_page): array {
        $target_page_value = array();
        $target_page_value["id"] = $target_page->getId();
        $target_page_value["title"] = $target_page->getTitle();
        return $target_page_value;
    }
}
