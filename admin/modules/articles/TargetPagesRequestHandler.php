<?php


defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/modules/articles/TargetPagesForm.php";

class TargetPagesRequestHandler extends HttpRequestHandler {

    private TargetPagesForm $_target_pages_form;
    private ArticleDao $_article_dao;

    public function __construct() {
        $this->_target_pages_form = new TargetPagesForm();
        $this->_article_dao = ArticleDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->_target_pages_form->loadFields();
        if ($this->isUpdateOptionsAction()) {
            $this->updateOptions();
        }
        if ($this->isChangeDefaultTargetPageAction()) {
            $this->changeDefaultTargetPage();
        }
        if ($this->isDeleteTargetPagesAction()) {
            $this->deleteTargetPages();
        }
    }

    private function deleteTargetPages(): void {
        foreach ($this->_target_pages_form->getTargetPagesToDelete() as $target_page_to_delete) {
            $this->_article_dao->deleteTargetPage($target_page_to_delete);
        }
    }

    private function changeDefaultTargetPage(): void {
        $new_default_target_page = $this->_target_pages_form->getNewDefaultTargetPage();
        if ($new_default_target_page != "") {
            $this->_article_dao->setDefaultArticleTargetPage($new_default_target_page);
        }
    }

    private function updateOptions(): void {
        $target_page_to_add = $this->_target_pages_form->getTargetPageToAdd();
        if ($target_page_to_add != "") {
            $this->_article_dao->addTargetPage($target_page_to_add);
        }
    }

    private function isUpdateOptionsAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "target_page_to_add";
    }

    private function isChangeDefaultTargetPageAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "change_default_target_page";
    }

    private function isDeleteTargetPagesAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_target_pages";
    }
}

?>